<?php

namespace App\GraphQL\Mutations;

use App\Models\Cotizacion;

class UpdateCotizacionesStatus
{
    /**
     * @param  null  $_
     * @param  array{ids: array<int>, estado: string}  $args
     */
    public function __invoke($_, array $args)
    {
        $updated = Cotizacion::whereIn('id', $args['ids'])
            ->update(['estado' => $args['estado']]);

        return [
            'success' => true,
            'message' => "Se han actualizado {$updated} cotizaciones correctamente."
        ];
    }
}
