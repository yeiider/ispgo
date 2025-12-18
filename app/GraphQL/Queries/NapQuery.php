<?php

namespace App\GraphQL\Queries;

use Ispgo\NapManager\Models\NapBox;
use Ispgo\NapManager\Models\NapPort;

class NapQuery
{
    public function napBoxes($root, array $args)
    {
        $query = NapBox::query()->withCount([
            'ports as available_ports_count' => function ($q) { $q->where('status', 'available'); },
        ])->with(['ports.service']);

        if (isset($args['router_id'])) {
            $query->where('router_id', $args['router_id']);
        }

        // Paginación
        $first = $args['first'] ?? 15;
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
}
