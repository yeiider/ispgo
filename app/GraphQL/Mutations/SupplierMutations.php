<?php

namespace App\GraphQL\Mutations;

use App\Models\Inventory\Supplier;
use Exception;

class SupplierMutations
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args)
    {
        try {
            $supplier = Supplier::create($args['input']);

            return [
                'success' => true,
                'message' => 'Proveedor creado exitosamente',
                'supplier' => $supplier,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al crear el proveedor: ' . $e->getMessage(),
                'supplier' => null,
            ];
        }
    }
}
