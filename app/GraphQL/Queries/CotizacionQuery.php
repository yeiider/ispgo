<?php

namespace App\GraphQL\Queries;

use App\Models\Cotizacion;
use Illuminate\Database\Eloquent\Builder;

class CotizacionQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): Builder
    {
        $query = Cotizacion::query();

        if (!empty($args['estado'])) {
            $query->where('estado', $args['estado']);
        }

        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%")
                  ->orWhere('ciudad', 'like', "%{$search}%")
                  ->orWhere('plan', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortColumn = $args['sort_column'] ?? 'created_at';
        $sortDirection = isset($args['sort_direction']) && strtolower($args['sort_direction']) === 'asc' ? 'asc' : 'desc';

        $allowedSortColumns = ['id', 'nombre', 'apellido', 'email', 'telefono', 'ciudad', 'plan', 'canal', 'estado', 'created_at'];
        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
