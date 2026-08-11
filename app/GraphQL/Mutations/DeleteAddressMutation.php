<?php

namespace App\GraphQL\Mutations;

use App\Models\Customers\Address;
use App\Models\Services\Service;
use Illuminate\Support\Facades\Auth;
use Exception;

class DeleteAddressMutation
{
    /**
     * Resolve the deleteAddress mutation.
     *
     * @param  null  $rootValue
     * @param  array{id: string}  $args
     * @return Address
     * @throws Exception
     */
    public function resolve($rootValue, array $args): Address
    {
        $id = $args['id'];

        // Find the address without the global router filter scope
        // so that we can check permission / existence properly.
        $address = Address::withoutGlobalScope('router_filter')->find($id);

        if (!$address) {
            throw new Exception('Dirección no encontrada.');
        }

        // Authorization check if the user has router restrictions
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $routerIds = $user->getRouterIds();
            if (!empty($routerIds)) {
                $customer = $address->customer;
                if ($customer) {
                    $hasServiceInRouter = $customer->services()->whereIn('router_id', $routerIds)->exists();
                    $isCustomerInRouter = in_array($customer->router_id, $routerIds);
                    if (!$hasServiceInRouter && !$isCustomerInRouter) {
                        throw new Exception('No tiene permisos para eliminar esta dirección.');
                    }
                }
            }
        }

        // Check if there are services linked to this address
        $hasServices = Service::where('service_location', $address->id)->exists();

        if ($hasServices) {
            throw new Exception('No se puede eliminar la dirección porque está vinculada a uno o más servicios. Por favor, cambie la ubicación de los servicios asociados antes de eliminar la dirección.');
        }

        $address->delete();

        return $address;
    }
}
