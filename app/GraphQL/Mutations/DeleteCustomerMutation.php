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

            // Verificar si tiene facturas pagadas o servicios activos
            $hasActiveServices = $customer->services()
                ->whereIn('service_status', ['active', 'suspended'])
                ->exists();

            $hasPaidInvoices = $customer->invoices()
                ->where('status', 'paid')
                ->exists();

            if ($hasActiveServices) {
                throw new \Exception('No se puede eliminar el cliente porque tiene servicios activos. Por favor, desactiva los servicios primero.');
            }

            if ($hasPaidInvoices) {
                throw new \Exception('No se puede eliminar el cliente porque tiene facturas pagadas en el historial. Considera desactivar el cliente en lugar de eliminarlo.');
            }

            // Eliminar relaciones en orden
            // Primero eliminar servicios y sus facturas (los servicios referencian addresses)
            foreach ($customer->services as $service) {
                $service->invoices()->delete();
                $service->delete();
            }

            // Ahora sí podemos eliminar las direcciones
            $customer->addresses()->delete();

            // Eliminar contratos
            $customer->contracts()->delete();

            // Eliminar facturas restantes
            $customer->invoices()->delete();

            // Eliminar detalles fiscales si existen
            if ($customer->taxDetails) {
                $customer->taxDetails->delete();
            }

            // Finalmente eliminar el cliente
            $customer->delete();

            DB::commit();

            return $customer;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
