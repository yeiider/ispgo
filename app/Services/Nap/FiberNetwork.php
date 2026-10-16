<?php

namespace App\Services\Nap;

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

    public function build(?int $routerId = null): array
    {
        $boxes = NapBox::query()
            ->when($routerId, fn ($q) => $q->where('router_id', $routerId))
            ->with(['ports' => fn ($q) => $q->orderBy('port_number')])
            ->get()
            ->keyBy('id');

        $olts = Olt::query()
            ->when($routerId, fn ($q) => $q->where('router_id', $routerId))
            ->get()
            ->keyBy('id');

        [$onus, $live, $liveError] = $this->onuStates($olts->where('is_active', true)->keys()->all());

        $ports = $boxes->flatMap(fn (NapBox $b) => $b->ports)->filter(fn (NapPort $p) => $p->service_id);
        $services = Service::with(['customer.addresses', 'address'])
            ->whereIn('id', $ports->pluck('service_id')->unique())
            ->get()
            ->keyBy('id');

        // Datos de cada caja resueltos a partir de su cadena de padres
        $resolved = [];
        foreach ($boxes as $box) {
            $resolved[$box->id] = $this->resolveChain($box, $boxes);
        }

        $clients = [];
        foreach ($ports as $port) {
            $service = $services->get($port->service_id);
            if (!$service) {
                continue;
            }
            $box = $boxes->get($port->nap_box_id);
            $serial = strtoupper(trim((string) $service->sn));
            $onu = $serial !== '' ? ($onus[$serial] ?? null) : null;
            [$lat, $lng, $address] = $this->serviceLocation($service);
            $expectedPon = $resolved[$box->id]['pon_port'];

            $clients[] = [
                'service_id' => $service->id,
                'customer_id' => $service->customer_id,
                'customer_name' => trim(($service->customer->first_name ?? '') . ' ' . ($service->customer->last_name ?? '')),
                'address' => $address,
                'latitude' => $lat,
                'longitude' => $lng,
                'nap_box_id' => $box->id,
                'nap_port_id' => $port->id,
                'port_number' => $port->port_number,
                'sn' => $service->sn,
                'service_status' => $service->service_status,
                'onu_state' => $onu['state'] ?? ($live && $serial !== '' ? 'not_found' : 'unknown'),
                'rx_power_dbm' => $onu['rx_power_dbm'] ?? null,
                'onu_port' => $onu['port'] ?? null,
                'onu_olt_id' => $onu['olt_id'] ?? null,
                'port_mismatch' => $onu && $expectedPon && $onu['port']
                    && $this->normalizePon($onu['port']) !== $this->normalizePon($expectedPon),
            ];
        }

        $byBox = collect($clients)->groupBy('nap_box_id');
        $children = $boxes->groupBy(fn ($b) => $b->parent_nap_id && $boxes->has($b->parent_nap_id) ? $b->parent_nap_id : 0);

        $direct = [];
        foreach ($boxes as $box) {
            $direct[$box->id] = $this->count($byBox->get($box->id, collect()));
        }
        $subtree = [];
        $sum = function (int $id, array $seen = []) use (&$sum, &$subtree, $direct, $children) {
            if (isset($subtree[$id])) {
                return $subtree[$id];
            }
            $total = $direct[$id];
            foreach ($children->get($id, collect()) as $child) {
                if (in_array($child->id, $seen)) {
                    continue;
                }
                $total = $this->addCounts($total, $sum($child->id, [...$seen, $id]));
            }

            return $subtree[$id] = $total;
        };

        $boxRows = [];
        foreach ($boxes as $box) {
            $tree = $sum($box->id);
            $used = $box->ports->whereNotNull('service_id')->count();
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
                'route' => $box->route,
                'cable_length_m' => $box->cable_length_m,
                'children_count' => $children->get($box->id, collect())->count(),
                'direct' => $direct[$box->id],
                'subtree' => $tree,
                'health' => $this->health($tree),
                'direct_health' => $this->health($direct[$box->id]),
            ];
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
            'boxes' => array_values($boxRows),
            'clients' => $clients,
            'incidents' => $this->incidents($boxRows, $clients, $olts),
        ];
    }

    /**
     * Estado de cada ONU de las OLTs indicadas, indexado por serial.
     *
     * @return array{0: array<string, array>, 1: bool, 2: ?string}
     */
    private function onuStates(array $oltIds): array
    {
        if (!$oltIds) {
            return [[], false, 'No hay OLTs activas configuradas'];
        }

        $states = [];
        $errors = [];
        $okCount = 0;
        foreach ($oltIds as $oltId) {
            try {
                $list = Cache::remember("nap-network:onus:{$oltId}", self::ONU_CACHE_SECONDS, fn () => $this->oltgo->onus((int) $oltId));
                $okCount++;
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
                    : $e->getMessage();
            }
        }

        return [$states, $okCount > 0, $errors ? $errors[0] : null];
    }

    /**
     * OLT, puerto PON, profundidad y camino (ids desde la raíz) de una caja.
     */
    private function resolveChain(NapBox $box, Collection $boxes): array
    {
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

        return ['olt_id' => $oltId, 'pon_port' => $pon, 'depth' => count($path) - 1, 'path' => $path];
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

    private function count(Collection $clients): array
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

    private function incidents(array $boxes, array $clients, Collection $olts): array
    {
        $incidents = [];

        // Troncal PON caída: todas las cajas raíz de un mismo puerto PON están caídas
        $roots = collect($boxes)->whereNull('parent_nap_id')->filter(fn ($b) => $b['olt_id'] && $b['pon_port']);
        $ponDown = [];
        foreach ($roots->groupBy(fn ($b) => $b['olt_id'] . '|' . $b['pon_port']) as $key => $group) {
            if ($group->count() < 2 || $group->contains(fn ($b) => $b['health'] !== 'down')) {
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
            if ($box['health'] !== 'down' || in_array($box['id'], $ponDown)) {
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
