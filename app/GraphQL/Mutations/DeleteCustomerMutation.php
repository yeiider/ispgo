<?php

namespace App\GraphQL\Mutations;

use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;

class DeleteCustomerMutation
{
    public function resolve($rootValue, array $args)
    {
        $customerId = $args['id'];

        $customer = Customer::find($customerId);

        if (!$customer) {
            throw new \Exception('Cliente no encontrado.');
        }

        try {
            DB::beginTransaction();

            // Actualizar estado a inactivo
            $customer->update(['customer_status' => 'inactive']);

            // Actualizar estado de servicios asociados a inactivo
            foreach ($customer->services as $service) {
                $service->update(['service_status' => 'inactive']);
            }

            // Ejecutar borrado lógico en cascada (SoftDelete)
            $customer->delete();

            DB::commit();

            return $customer;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
