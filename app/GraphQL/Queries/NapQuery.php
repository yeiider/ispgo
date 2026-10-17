<?php

namespace App\GraphQL\Queries;

use App\Services\Nap\FiberNetwork;
use Ispgo\NapManager\Models\NapBox;
use Ispgo\NapManager\Models\NapPort;

class NapQuery
{
    public function network($root, array $args): array
    {
        return app(FiberNetwork::class)->build(!empty($args['router_id']) ? (int) $args['router_id'] : null);
    }

    /**
     * Cajas desde la raíz (la que sale de la OLT) hasta $root, incluida.
     */
    public function chain(NapBox $root): array
    {
        $chain = [];
        $box = $root;
        $seen = [];
        while ($box && !in_array($box->id, $seen)) {
            $seen[] = $box->id;
            array_unshift($chain, $box);
            $box = $box->parentNap;
        }

        return $chain;
    }

    public function resolvedOlt(NapBox $root): ?\App\Models\Olt
    {
        $oltId = $root->resolvedOltId();

        return $oltId ? \App\Models\Olt::find($oltId) : null;
    }

    public function resolvedPonPort(NapBox $root): ?string
    {
        foreach (array_reverse($this->chain($root)) as $box) {
            if ($box->pon_port) {
                return $box->pon_port;
            }
        }

        return null;
    }

    public function boxClients($root, array $args): array
    {
        return app(FiberNetwork::class)->clients($args['nap_box_ids']);
    }

    public function networkSearch($root, array $args): array
    {
        return app(FiberNetwork::class)->search($args['search'], !empty($args['router_id']) ? (int) $args['router_id'] : null);
    }

    public function stats($root, array $args): array
    {
        $router = $args['router_id'] ?? null;
        $ports = NapPort::query()
            ->when($router, fn ($q) => $q->whereIn('nap_box_id', NapBox::where('router_id', $router)->select('id')))
            ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return [
            'boxes' => NapBox::when($router, fn ($q) => $q->where('router_id', $router))->count(),
            'ports' => (int) $ports->sum(),
            'available' => (int) ($ports['available'] ?? 0),
            'occupied' => (int) ($ports['occupied'] ?? 0),
            'problem' => (int) (($ports['damaged'] ?? 0) + ($ports['maintenance'] ?? 0)),
        ];
    }

    public function napBoxSearch($root, array $args)
    {
        $term = trim($args['search'] ?? '');

        return NapBox::query()
            ->when(!empty($args['router_id']), fn ($q) => $q->where('router_id', $args['router_id']))
            ->when($term !== '', fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%")))
            ->orderBy('name')
            ->limit(min((int) ($args['limit'] ?? 20), 30))
            ->get();
    }

    public function napBoxes($root, array $args)
    {
        $query = NapBox::query()->withCount([
            'ports as available_ports_count' => function ($q) { $q->where('status', 'available'); },
        ])->with(['ports.service.customer']);

        if (isset($args['router_id'])) {
            $query->where('router_id', $args['router_id']);
        }
        if (!empty($args['status'])) {
            $query->where('status', $args['status']);
        }
        if (!empty($args['olt_id'])) {
            $query->where('olt_id', $args['olt_id']);
        }
        if (!empty($args['fiber_node_id'])) {
            $query->where('fiber_node_id', $args['fiber_node_id']);
        }
        if (($term = trim($args['search'] ?? '')) !== '') {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('address', 'like', "%{$term}%"));
        }
        $query->orderBy('name');

        // Paginación
        $first = min((int) ($args['first'] ?? 15), 1000);
        $page = $args['page'] ?? 1;
        $paginator = $query->paginate($first, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'count' => $paginator->count(),
                'currentPage' => $paginator->currentPage(),
                'firstItem' => $paginator->firstItem(),
                'hasMorePages' => $paginator->hasMorePages(),
                'lastItem' => $paginator->lastItem(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

    public function napBox($root, array $args)
    {
        return NapBox::with(['ports.service'])->find($args['id']);
    }

    public function napPorts($root, array $args)
    {
        return NapPort::where('nap_box_id', $args['nap_box_id'])->orderBy('port_number')->get();
    }

    public function napPort($root, array $args)
    {
        return NapPort::find($args['id']);
    }

    public function availableNapPorts($root, array $args)
    {
        return NapPort::where('nap_box_id', $args['nap_box_id'])
            ->where('status', NapPort::STATUS_AVAILABLE)
            ->orderBy('port_number')
            ->get();
    }

    /**
     * Lista de servicios asignados a una caja, útil para vistas tipo araña.
     * Puede usarse como field resolver (con $root NapBox) o query con arg nap_box_id.
     */
    public function napBoxServices($root, array $args)
    {
        $napBoxId = $args['nap_box_id'] ?? ($root->id ?? null);
        if (!$napBoxId) {
            return [];
        }

        // Traer puertos con servicio y mapear a los servicios (únicos)
        $ports = NapPort::with('service')
            ->where('nap_box_id', $napBoxId)
            ->whereNotNull('service_id')
            ->get();

        // Filtrar nulos y devolver solo modelos Service; Lighthouse permite solicitar
        // campos de relaciones (si el tipo Service los tiene en el schema).
        return $ports->pluck('service')->filter()->values();
    }

    public function nearbyNapBoxes($root, array $args)
    {
        $lat = $args['latitude'];
        $lng = $args['longitude'];
        $radiusMeters = $args['radius'] ?? 500;
        $radiusKm = $radiusMeters / 1000;

        // Fórmula Haversine para calcular distancia en km
        return NapBox::select('*')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$lat, $lng, $lat]
            )
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance')
            ->get();
    }
}
