<?php

namespace App\Services\Nap;

use App\Models\FiberCable;
use App\Models\FiberNode;
use App\Models\Olt;
use App\Models\Services\Service;
use App\Services\Oltgo\OltgoClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Ispgo\NapManager\Models\NapBox;
use Ispgo\NapManager\Models\NapPort;
use Throwable;

/**
 * Red de fibra completa (OLT → puerto PON → cajas en cascada → clientes) con el
 * estado de cada ONU según OLTGO, y los incidentes que se deducen de ese estado:
 * si todas las ONUs que cuelgan de un tramo están caídas, el problema está en ese tramo.
 */
class FiberNetwork
{
    /** Por debajo de esto la potencia se considera baja (dBm). */
    public const WEAK_RX_DBM = -27.0;

    private const ONU_CACHE_SECONDS = 30;

    public function __construct(private OltgoClient $oltgo)
    {
    }

    /** La red completa se recalcula como mucho cada tantos segundos (por usuario y zona). */
    private const NETWORK_CACHE_SECONDS = 20;

    public function build(?int $routerId = null): array
    {
        $key = 'nap-network:build:' . (auth()->id() ?? 0) . ':' . ($routerId ?? 'all');

        return Cache::remember($key, self::NETWORK_CACHE_SECONDS, fn () => $this->compute($routerId));
    }

    /**
     * Clientes completos (nombre, dirección, coordenadas, estado ONU) de unas cajas.
     * El mapa los pide solo para lo que está viendo, no para toda la red.
     */
    public function clients(array $boxIds): array
    {
        $boxIds = array_slice(array_values(array_unique(array_map('intval', $boxIds))), 0, 300);
        if (!$boxIds) {
            return [];
        }
        $boxes = NapBox::query()->get(['id', 'parent_nap_id', 'olt_id', 'pon_port', 'fiber_node_id', 'fiber_cable_id'])->keyBy('id');
        $nodes = FiberNode::query()->get()->keyBy('id');
        $cables = FiberCable::query()->get()->keyBy('id');
        $nodePaths = $this->nodePaths($nodes, $cables);
        [$onus, , , $okOlts] = $this->onuStates(Olt::where('is_active', true)->pluck('id')->all());

        $ports = NapPort::whereIn('nap_box_id', $boxIds)->whereNotNull('service_id')->get();
        $services = Service::with(['customer.addresses', 'address'])->whereIn('id', $ports->pluck('service_id'))->get()->keyBy('id');

        $rows = [];
        foreach ($ports as $port) {
            $service = $services->get($port->service_id);
            $box = $boxes->get($port->nap_box_id);
            if (!$service || !$box) {
                continue;
            }
            $resolved = $this->resolveChain($box, $boxes, $nodePaths);
            [$lat, $lng, $address] = $this->serviceLocation($service);
            $rows[] = $this->clientRow($service, $port->id, $port->nap_box_id, $port->port_number, $resolved, $onus, $okOlts) + [
                'customer_id' => $service->customer_id,
                'customer_name' => trim(($service->customer->first_name ?? '') . ' ' . ($service->customer->last_name ?? '')),
                'address' => $address,
                'latitude' => $lat,
                'longitude' => $lng,
            ];
        }

        return $rows;
    }

    /**
     * Búsqueda de clientes (nombre, documento, serial ONU, dirección) y cajas, para ubicarlos en el mapa.
     */
    public function search(string $term, ?int $routerId = null): array
    {
        $term = trim($term);
        if (mb_strlen($term) < 2) {
            return ['boxes' => [], 'clients' => []];
        }
        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        $boxes = NapBox::query()
            ->when($routerId, fn ($q) => $q->where('router_id', $routerId))
            ->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('code', 'like', $like)->orWhere('address', 'like', $like))
            ->limit(10)
            ->pluck('id')
            ->all();

        $serviceIds = Service::query()
            ->join('nap_ports', 'nap_ports.service_id', '=', 'services.id')
            ->leftJoin('customers', 'customers.id', '=', 'services.customer_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'services.service_location')
            ->when($routerId, fn ($q) => $q->where('services.router_id', $routerId))
            ->where(function ($q) use ($like) {
                $q->where('services.sn', 'like', $like)
                    ->orWhere('customers.identity_document', 'like', $like)
                    ->orWhere('addresses.address', 'like', $like)
                    ->orWhereRaw("CONCAT_WS(' ', customers.first_name, customers.last_name) LIKE ?", [$like]);
            })
            ->limit(30)
            ->pluck('nap_ports.nap_box_id', 'services.id');

        $clients = collect($this->clients($serviceIds->values()->all()))
            ->whereIn('service_id', $serviceIds->keys()->all())
            ->values()
            ->all();

        return ['boxes' => $boxes, 'clients' => $clients];
    }

    /** @param Service|object $service */
    private function clientRow(object $service, int $portId, int $boxId, int $portNumber, array $resolved, array $onus, array $okOlts): array
    {
        $serial = strtoupper(trim((string) $service->sn));
        $onu = $serial !== '' ? ($onus[$serial] ?? null) : null;
        $expectedPon = $resolved['pon_port'];

        return [
            'service_id' => $service->id,
            'nap_box_id' => $boxId,
            'nap_port_id' => $portId,
            'port_number' => $portNumber,
            'sn' => $service->sn,
            'service_status' => $service->service_status,
            // "No está en la OLT" solo si se pudo leer la OLT de esa caja
            'onu_state' => $onu['state'] ?? ($serial !== '' && in_array($resolved['olt_id'], $okOlts) ? 'not_found' : 'unknown'),
            'rx_power_dbm' => $onu['rx_power_dbm'] ?? null,
            'onu_port' => $onu['port'] ?? null,
            'onu_olt_id' => $onu['olt_id'] ?? null,
            'port_mismatch' => $onu && $expectedPon && $onu['port']
                && $this->normalizePon($onu['port']) !== $this->normalizePon($expectedPon),
        ];
    }

    private function compute(?int $routerId = null): array
    {
        // Se arma la red completa (los troncales cruzan zonas) y al final se filtra por zona.
        // Para miles de cajas solo se leen columnas livianas: los clientes completos se piden aparte.
        $boxes = NapBox::query()->get()->keyBy('id');
        $usedPorts = NapPort::query()->whereNotNull('service_id')
            ->selectRaw('nap_box_id, count(*) as used')->groupBy('nap_box_id')->pluck('used', 'nap_box_id');

        $olts = Olt::query()->get()->keyBy('id');
        $nodes = FiberNode::query()->get()->keyBy('id');
        $cables = FiberCable::query()->get()->keyBy('id');
        $nodePaths = $this->nodePaths($nodes, $cables);

        [$onus, $live, $liveError, $okOlts] = $this->onuStates($olts->where('is_active', true)->keys()->all());

        // Servicio + puerto en una sola consulta liviana (respeta el filtro de zonas del usuario)
        $links = Service::query()
            ->join('nap_ports', 'nap_ports.service_id', '=', 'services.id')
            ->toBase()
            ->get(['services.id', 'services.sn', 'services.service_status', 'nap_ports.id as port_id', 'nap_ports.nap_box_id', 'nap_ports.port_number']);

        // Datos de cada caja resueltos a partir de su cadena de padres
        $resolved = [];
        foreach ($boxes as $box) {
            $resolved[$box->id] = $this->resolveChain($box, $boxes, $nodePaths, $resolved);
        }

        $clients = [];
        foreach ($links as $link) {
            $box = $boxes->get($link->nap_box_id);
            if (!$box) {
                continue;
            }
            $clients[] = $this->clientRow($link, $link->port_id, $link->nap_box_id, $link->port_number, $resolved[$box->id], $onus, $okOlts);
        }

        $byBox = [];
        foreach ($clients as $client) {
            $byBox[$client['nap_box_id']][] = $client;
        }
        $childrenCount = [];
        foreach ($boxes as $box) {
            if ($box->parent_nap_id && $boxes->has($box->parent_nap_id)) {
                $childrenCount[$box->parent_nap_id] = ($childrenCount[$box->parent_nap_id] ?? 0) + 1;
            }
        }

        $direct = [];
        foreach ($boxes as $box) {
            $direct[$box->id] = $this->count($byBox[$box->id] ?? []);
        }
        // Suma aguas abajo en una pasada: de las cajas más profundas hacia la raíz
        $subtree = $direct;
        $byDepth = $boxes->sortByDesc(fn ($b) => $resolved[$b->id]['depth']);
        foreach ($byDepth as $box) {
            $path = $resolved[$box->id]['path'];
            $parentId = count($path) > 1 ? $path[count($path) - 2] : null;
            if ($parentId && isset($subtree[$parentId])) {
                $subtree[$parentId] = $this->addCounts($subtree[$parentId], $subtree[$box->id]);
            }
        }

        $boxRows = [];
        foreach ($boxes as $model) {
            // Atributos crudos: los casts (decimal, json) por miles de cajas pesan
            $box = (object) $model->getAttributes();
            $tree = $subtree[$box->id];
            $used = (int) ($usedPorts[$box->id] ?? 0);
            $boxRows[$box->id] = [
                'id' => $box->id,
                'name' => $box->name,
                'code' => $box->code,
                'address' => $box->address,
                'latitude' => $box->latitude !== null ? (float) $box->latitude : null,
                'longitude' => $box->longitude !== null ? (float) $box->longitude : null,
                'status' => $box->status,
                'capacity' => (int) $box->capacity,
                'used_ports' => $used,
                'splitter_ratio' => $box->splitter_ratio,
                'fiber_color' => $box->fiber_color,
                'technology_type' => $box->technology_type,
                'router_id' => $box->router_id,
                'own_olt_id' => $box->olt_id,
                'own_pon_port' => $box->pon_port,
                'olt_id' => $resolved[$box->id]['olt_id'],
                'pon_port' => $resolved[$box->id]['pon_port'],
                'parent_nap_id' => $box->parent_nap_id && $boxes->has($box->parent_nap_id) ? $box->parent_nap_id : null,
                'depth' => $resolved[$box->id]['depth'],
                'path' => $resolved[$box->id]['path'],
                'route' => $box->route ? json_decode($box->route, true) : null,
                'cable_length_m' => $box->cable_length_m,
                'fiber_node_id' => $box->fiber_node_id,
                'fiber_cable_id' => $box->fiber_cable_id,
                'fiber_strand' => $box->fiber_strand,
                'cable_path' => $resolved[$box->id]['cable_path'],
                'node_path' => $resolved[$box->id]['node_path'],
                'children_count' => $childrenCount[$box->id] ?? 0,
                'direct' => $direct[$box->id],
                'subtree' => $tree,
                'health' => $this->health($tree),
                'direct_health' => $this->health($direct[$box->id]),
            ];
        }

        // Lo que cuelga de cada cable y de cada nodo (sumando las cajas raíz)
        $roots = collect($boxRows)->where('depth', 0);
        $cableRows = [];
        foreach ($cables as $cable) {
            $down = $roots->filter(fn ($b) => in_array($cable->id, $b['cable_path']));
            $counts = $down->reduce(fn ($acc, $b) => $this->addCounts($acc, $b['subtree']), $this->count(collect()));
            $used = $down->filter(fn ($b) => $b['fiber_cable_id'] == $cable->id && $b['fiber_strand'])
                ->map(fn ($b) => ['strand' => $b['fiber_strand'], 'nap_box_id' => $b['id']])->values()->all();
            $cableRows[$cable->id] = [
                'id' => $cable->id,
                'name' => $cable->name,
                'code' => $cable->code,
                'kind' => $cable->kind,
                'fiber_count' => $cable->fiber_count,
                'from_node_id' => $cable->from_node_id,
                'to_node_id' => $cable->to_node_id,
                'route' => $cable->route,
                'length_m' => $cable->length_m,
                'status' => $cable->status,
                'notes' => $cable->notes,
                'box_ids' => $down->pluck('id')->values()->all(),
                'strands' => $used,
                'downstream' => $counts,
                'health' => $this->health($counts),
            ];
        }
        $nodeRows = [];
        foreach ($nodes as $node) {
            $down = $roots->filter(fn ($b) => in_array($node->id, $b['node_path']));
            $counts = $down->reduce(fn ($acc, $b) => $this->addCounts($acc, $b['subtree']), $this->count(collect()));
            $nodeRows[$node->id] = [
                'id' => $node->id,
                'name' => $node->name,
                'code' => $node->code,
                'type' => $node->type,
                'latitude' => $node->latitude,
                'longitude' => $node->longitude,
                'address' => $node->address,
                'olt_id' => $nodePaths[$node->id]['olt_id'],
                'own_olt_id' => $node->olt_id,
                'router_id' => $nodePaths[$node->id]['router_id'],
                'own_router_id' => $node->router_id,
                'provider' => $node->provider,
                'capacity_mbps' => $node->capacity_mbps,
                'notes' => $node->notes,
                'cable_path' => $nodePaths[$node->id]['cables'],
                'box_ids' => $down->pluck('id')->values()->all(),
                'downstream' => $counts,
                'health' => $this->health($counts),
            ];
        }

        $incidents = $this->incidents($boxRows, $clients, $olts, $cableRows, $nodeRows);

        // Filtro por zona: cajas y clientes de la zona; troncales y nodos siempre (son generales)
        if ($routerId) {
            $inZone = collect($boxRows)->where('router_id', $routerId)->pluck('id')->all();
            $boxRows = array_filter($boxRows, fn ($b) => in_array($b['id'], $inZone));
            $clients = array_values(array_filter($clients, fn ($c) => in_array($c['nap_box_id'], $inZone)));
            $incidents = array_values(array_filter($incidents, fn ($i) => array_intersect($i['box_ids'], $inZone)));
        }

        return [
            'live' => $live,
            'live_error' => $liveError,
            'checked_at' => now()->toIso8601String(),
            'olts' => $olts->map(fn (Olt $o) => [
                'id' => $o->id,
                'name' => $o->name,
                'latitude' => $o->latitude,
                'longitude' => $o->longitude,
                'address' => $o->address,
                'is_active' => $o->is_active,
            ])->values()->all(),
            'nodes' => array_values($nodeRows),
            'cables' => array_values($cableRows),
            'boxes' => array_values($boxRows),
            // Con miles de clientes no se mandan todos: el mapa los pide por caja (clients()/search())
            'clients' => [],
            'incidents' => $incidents,
        ];
    }

    /**
     * Estado de cada ONU de las OLTs indicadas, indexado por serial.
     *
     * @return array{0: array<string, array>, 1: bool, 2: ?string, 3: int[]}
     */
    private function onuStates(array $oltIds): array
    {
        if (!$oltIds) {
            return [[], false, 'No hay OLTs activas configuradas', []];
        }

        $states = [];
        $errors = [];
        $okCount = 0;
        $okOlts = [];
        foreach ($oltIds as $oltId) {
            try {
                // Una OLT que no responde no debe frenar cada carga del mapa: se recuerda el fallo un rato
                if ($failed = Cache::get("nap-network:onus-failed:{$oltId}")) {
                    throw new \RuntimeException($failed);
                }
                try {
                    $list = Cache::remember("nap-network:onus:{$oltId}", self::ONU_CACHE_SECONDS, fn () => $this->oltgo->onus((int) $oltId));
                } catch (Throwable $e) {
                    Cache::put("nap-network:onus-failed:{$oltId}", $e->getMessage(), 120);
                    throw $e;
                }
                $okCount++;
                $okOlts[] = (int) $oltId;
                foreach ($list['onus'] ?? [] as $onu) {
                    $serial = strtoupper(trim((string) ($onu['serial'] ?? '')));
                    if ($serial !== '') {
                        $states[$serial] = [
                            'state' => $onu['state'] ?? 'unknown',
                            'rx_power_dbm' => $onu['rx_power_dbm'] ?? null,
                            'port' => $onu['port'] ?? null,
                            'olt_id' => $oltId,
                        ];
                    }
                }
            } catch (Throwable $e) {
                $errors[] = str_contains($e->getMessage(), 'cURL error')
                    ? 'OLTGO no responde en ' . \App\Settings\Oltgo\ProviderOltgo::url()
                    : (Olt::find($oltId)?->name ?? "OLT {$oltId}") . ': ' . $e->getMessage();
            }
        }

        return [$states, $okCount > 0, $errors ? $errors[0] : null, $okOlts];
    }

    /**
     * OLT, puerto PON, profundidad y camino (ids desde la raíz) de una caja.
     */
    private function resolveChain(NapBox $box, Collection $boxes, array $nodePaths, array &$memo = []): array
    {
        // Si el padre ya está resuelto, se hereda de él (miles de cajas en cascada)
        $parent = $box->parent_nap_id ? $boxes->get($box->parent_nap_id) : null;
        if ($parent && isset($memo[$parent->id]) && !in_array($box->id, $memo[$parent->id]['path'])) {
            $p = $memo[$parent->id];

            return [
                'olt_id' => $box->olt_id ?: $p['olt_id'],
                'pon_port' => $box->pon_port ?: $p['pon_port'],
                'depth' => $p['depth'] + 1,
                'path' => [...$p['path'], $box->id],
                'cable_path' => $p['cable_path'],
                'node_path' => $p['node_path'],
            ];
        }

        $path = [];
        $oltId = null;
        $pon = null;
        $current = $box;
        $seen = [];
        while ($current && !in_array($current->id, $seen)) {
            $seen[] = $current->id;
            array_unshift($path, $current->id);
            $oltId ??= $current->olt_id;
            $pon ??= $current->pon_port;
            $current = $current->parent_nap_id ? $boxes->get($current->parent_nap_id) : null;
        }

        // La caja raíz se empalma en una mufla: la OLT y los cables vienen de ahí hacia arriba
        $root = $boxes->get($path[0]);
        $cablePath = [];
        $nodePath = [];
        if ($root?->fiber_node_id && isset($nodePaths[$root->fiber_node_id])) {
            $np = $nodePaths[$root->fiber_node_id];
            $oltId ??= $np['olt_id'];
            $cablePath = $np['cables'];
            $nodePath = $np['nodes'];
        }
        if ($root?->fiber_cable_id && !in_array($root->fiber_cable_id, $cablePath)) {
            $cablePath[] = $root->fiber_cable_id;
        }

        return [
            'olt_id' => $oltId,
            'pon_port' => $pon,
            'depth' => count($path) - 1,
            'path' => $path,
            'cable_path' => $cablePath,
            'node_path' => $nodePath,
        ];
    }

    /**
     * Para cada nodo: los cables y nodos desde la central hasta él, y la OLT de esa central.
     */
    private function nodePaths(Collection $nodes, Collection $cables): array
    {
        $incoming = $cables->groupBy('to_node_id');
        $paths = [];
        foreach ($nodes as $node) {
            $cablePath = [];
            $nodePath = [$node->id];
            // Se sube hasta el origen (datacenter): la OLT y la zona son las más cercanas hacia arriba
            $oltId = $node->olt_id;
            $routerId = $node->router_id;
            $current = $node;
            $seen = [$node->id];
            while ($cable = $incoming->get($current->id)?->first()) {
                $from = $nodes->get($cable->from_node_id);
                array_unshift($cablePath, $cable->id);
                if (!$from || in_array($from->id, $seen)) {
                    break;
                }
                array_unshift($nodePath, $from->id);
                $seen[] = $from->id;
                $oltId ??= $from->olt_id;
                $routerId ??= $from->router_id;
                $current = $from;
            }
            $paths[$node->id] = ['cables' => $cablePath, 'nodes' => $nodePath, 'olt_id' => $oltId, 'router_id' => $routerId];
        }

        return $paths;
    }

    /**
     * Dónde está el cliente: coordenadas de la ONU, luego la dirección del servicio, luego cualquier dirección del cliente.
     */
    private function serviceLocation(Service $service): array
    {
        if ($service->unu_latitude && $service->unu_longitude) {
            return [(float) $service->unu_latitude, (float) $service->unu_longitude, $service->address?->address];
        }
        $candidates = collect([$service->address])->merge($service->customer?->addresses ?? [])->filter();
        $withCoords = $candidates->first(fn ($a) => $a->latitude && $a->longitude);
        if ($withCoords) {
            return [(float) $withCoords->latitude, (float) $withCoords->longitude, $withCoords->address];
        }

        return [null, null, $candidates->first()?->address];
    }

    private function normalizePon(string $port): string
    {
        // gpon_olt-1/1/1, gpon-olt_1/1/1 y 1/1/1 son el mismo puerto
        return preg_match('#(\d+/\d+/\d+)#', $port, $m) ? $m[1] : strtolower($port);
    }

    private function count(iterable $clients): array
    {
        $c = ['total' => 0, 'online' => 0, 'offline' => 0, 'los' => 0, 'dying_gasp' => 0, 'unknown' => 0, 'weak' => 0];
        foreach ($clients as $client) {
            $c['total']++;
            $state = $client['onu_state'];
            if ($state === 'online') {
                $c['online']++;
                if ($client['rx_power_dbm'] !== null && $client['rx_power_dbm'] < self::WEAK_RX_DBM) {
                    $c['weak']++;
                }
            } elseif (in_array($state, ['unknown', 'not_found'])) {
                $c['unknown']++;
            } else {
                $c['offline']++;
                if ($state === 'los') {
                    $c['los']++;
                } elseif ($state === 'dying_gasp') {
                    $c['dying_gasp']++;
                }
            }
        }

        return $c;
    }

    private function addCounts(array $a, array $b): array
    {
        foreach ($b as $k => $v) {
            $a[$k] += $v;
        }

        return $a;
    }

    /**
     * empty: sin clientes · unknown: sin datos · ok · warning: alguna caída ·
     * critical: la mitad o más caídas · down: todas caídas.
     */
    private function health(array $c): string
    {
        $known = $c['online'] + $c['offline'];
        if ($c['total'] === 0) {
            return 'empty';
        }
        if ($known === 0) {
            return 'unknown';
        }
        if ($c['online'] === 0) {
            return 'down';
        }
        if ($c['offline'] / $known >= 0.5) {
            return 'critical';
        }

        return $c['offline'] > 0 ? 'warning' : 'ok';
    }

    private function incidents(array $boxes, array $clients, Collection $olts, array $cables = [], array $nodes = []): array
    {
        $incidents = [];

        // Cable troncal cortado: todo lo que cuelga del cable está caído y el cable anterior no
        $covered = [];
        $zones = \App\Models\Router::pluck('name', 'id');
        foreach ($cables as $cable) {
            if ($cable['health'] !== 'down' || $cable['downstream']['offline'] < 2) {
                continue;
            }
            $upstream = $nodes[$cable['from_node_id']]['cable_path'] ?? [];
            $parentCable = $upstream ? ($cables[end($upstream)] ?? null) : null;
            if ($parentCable && $parentCable['health'] === 'down') {
                continue;
            }
            $roots = $cable['box_ids'];
            $affectedBoxes = collect($boxes)->filter(fn ($b) => in_array($b['path'][0], $roots));
            $covered = array_merge($covered, $affectedBoxes->pluck('id')->all());
            $from = $nodes[$cable['from_node_id']]['name'] ?? '?';
            $to = $nodes[$cable['to_node_id']]['name'] ?? '?';
            $pons = $affectedBoxes->pluck('pon_port')->filter()->unique()->values();
            $zoneNames = $affectedBoxes->pluck('router_id')->filter()->unique()->map(fn ($id) => $zones[$id] ?? $id)->values();
            $power = $cable['downstream']['dying_gasp'] > $cable['downstream']['los'];
            $incidents[] = [
                'type' => $power ? 'power_outage' : 'trunk_cut',
                'severity' => 'critical',
                'nap_box_id' => null,
                'cable_id' => $cable['id'],
                'node_id' => null,
                'olt_id' => $affectedBoxes->first()['olt_id'] ?? null,
                'pon_port' => $pons->count() === 1 ? $pons->first() : null,
                'affected' => $cable['downstream']['offline'],
                'box_ids' => $affectedBoxes->pluck('id')->values()->all(),
                'title' => ($power ? 'Sin energía bajo ' : 'Corte en troncal ') . ($cable['code'] ?: $cable['name']),
                'detail' => "Tramo {$from} → {$to}: {$affectedBoxes->count()} cajas y {$cable['downstream']['offline']} ONUs caídas"
                    . ($zoneNames->isNotEmpty() ? ' en ' . $zoneNames->implode(', ') : '')
                    . ($pons->count() > 1 ? " ({$pons->count()} puertos PON)" : '')
                    . ($power ? '. Predomina dying gasp: probable corte de energía' : ''),
            ];
        }
        $covered = array_unique($covered);

        // Troncal PON caída: todas las cajas raíz de un mismo puerto PON están caídas
        $roots = collect($boxes)->whereNull('parent_nap_id')->filter(fn ($b) => $b['olt_id'] && $b['pon_port']);
        $ponDown = [];
        foreach ($roots->groupBy(fn ($b) => $b['olt_id'] . '|' . $b['pon_port']) as $key => $group) {
            if ($group->count() < 2 || $group->contains(fn ($b) => $b['health'] !== 'down' || in_array($b['id'], $covered))) {
                continue;
            }
            [$oltId, $pon] = explode('|', $key);
            $counts = $group->reduce(fn ($acc, $b) => $this->addCounts($acc, $b['subtree']), $this->count(collect()));
            $ponDown = array_merge($ponDown, $group->pluck('id')->all());
            $incidents[] = $this->incident(
                $counts,
                null,
                (int) $oltId,
                $pon,
                "Puerto PON {$pon} sin servicio",
                'Todas las cajas de este puerto están caídas: revisa la troncal o el puerto de la OLT ' . ($olts->get($oltId)?->name ?? ''),
                $group->pluck('id')->all(),
            );
        }

        // Tramo caído: la caja está caída y su padre no (el corte está entre ambos)
        foreach ($boxes as $box) {
            if ($box['health'] !== 'down' || in_array($box['id'], $ponDown) || in_array($box['id'], $covered)) {
                continue;
            }
            $parent = $box['parent_nap_id'] ? ($boxes[$box['parent_nap_id']] ?? null) : null;
            if ($parent && $parent['health'] === 'down') {
                continue;
            }
            $from = $parent ? $parent['name'] : ($box['olt_id'] ? ($olts->get($box['olt_id'])?->name ?? 'la OLT') : 'la OLT');
            $incidents[] = $this->incident(
                $box['subtree'],
                $box['id'],
                $box['olt_id'],
                $box['pon_port'],
                "{$box['name']} sin servicio",
                "Todas las ONUs de esta caja" . ($box['children_count'] ? ' y las que cuelgan de ella' : '') . " están caídas. Tramo sospechoso: {$from} → {$box['name']}",
                [$box['id']],
            );
        }

        // Caja con muchas caídas pero no todas (posible splitter, conector o drop)
        foreach ($boxes as $box) {
            if ($box['health'] !== 'critical' || $box['direct']['offline'] < 2) {
                continue;
            }
            $incidents[] = [
                'type' => 'partial',
                'severity' => 'warning',
                'cable_id' => null,
                'node_id' => null,
                'nap_box_id' => $box['id'],
                'olt_id' => $box['olt_id'],
                'pon_port' => $box['pon_port'],
                'affected' => $box['direct']['offline'],
                'box_ids' => [$box['id']],
                'title' => "{$box['name']}: {$box['direct']['offline']} de {$box['direct']['total']} ONUs caídas",
                'detail' => 'Más de la mitad de los clientes de la caja sin servicio: revisa splitter y conectores',
            ];
        }

        // Potencia baja y ONUs registradas en otro puerto PON (datos de la caja mal cargados)
        foreach (collect($clients)->groupBy('nap_box_id') as $boxId => $list) {
            $box = $boxes[$boxId] ?? null;
            if (!$box) {
                continue;
            }
            $weak = $list->filter(fn ($c) => $c['onu_state'] === 'online' && $c['rx_power_dbm'] !== null && $c['rx_power_dbm'] < self::WEAK_RX_DBM);
            if ($weak->count() >= 2) {
                $incidents[] = [
                    'type' => 'weak_signal',
                    'severity' => 'warning',
                    'cable_id' => null,
                    'node_id' => null,
                    'nap_box_id' => (int) $boxId,
                    'olt_id' => $box['olt_id'],
                    'pon_port' => $box['pon_port'],
                    'affected' => $weak->count(),
                    'box_ids' => [(int) $boxId],
                    'title' => "{$box['name']}: {$weak->count()} ONUs con potencia baja",
                    'detail' => 'Potencia por debajo de ' . self::WEAK_RX_DBM . ' dBm: posible conector sucio, curvatura o empalme en el tramo',
                ];
            }
            $mismatch = $list->where('port_mismatch', true);
            if ($mismatch->isNotEmpty()) {
                $incidents[] = [
                    'type' => 'port_mismatch',
                    'severity' => 'info',
                    'cable_id' => null,
                    'node_id' => null,
                    'nap_box_id' => (int) $boxId,
                    'olt_id' => $box['olt_id'],
                    'pon_port' => $box['pon_port'],
                    'affected' => $mismatch->count(),
                    'box_ids' => [(int) $boxId],
                    'title' => "{$box['name']}: {$mismatch->count()} ONUs en otro puerto PON",
                    'detail' => "La caja dice {$box['pon_port']} pero la OLT ve esas ONUs en " . $mismatch->pluck('onu_port')->unique()->implode(', ') . '. Corrige la caja o el puerto asignado',
                ];
            }
        }

        $order = ['critical' => 0, 'warning' => 1, 'info' => 2];
        usort($incidents, fn ($a, $b) => [$order[$a['severity']], -$a['affected']] <=> [$order[$b['severity']], -$b['affected']]);

        return $incidents;
    }

    private function incident(array $counts, ?int $boxId, ?int $oltId, ?string $pon, string $title, string $detail, array $boxIds): array
    {
        // Muchos dying gasp = se fue la luz en la zona; LOS = la fibra no llega
        $power = $counts['dying_gasp'] > $counts['los'];

        return [
            'type' => $power ? 'power_outage' : 'fiber_cut',
            'severity' => 'critical',
            'cable_id' => null,
            'node_id' => null,
            'nap_box_id' => $boxId,
            'olt_id' => $oltId,
            'pon_port' => $pon,
            'affected' => $counts['offline'],
            'box_ids' => $boxIds,
            'title' => $title,
            'detail' => $power ? $detail . '. Predomina dying gasp: probable corte de energía en la zona' : $detail,
        ];
    }
}
