<?php

namespace App\Console\Commands;

use App\Models\Customers\Address;
use Illuminate\Console\Command;

class PopulateCustomerNameForAddresses extends Command
{
    protected $signature = 'addresses:populate-customer-name';
    protected $description = 'Poblar la columna customer_name para las direcciones existentes';

    public function handle()
    {
        $this->info('Actualizando los nombres de clientes para las direcciones existentes...');

        Address::with('customer')->chunk(100, function ($addresses) {
            foreach ($addresses as $address) {
                if ($address->customer) {
                    $address->customer_name = $address->customer->first_name . ' ' . $address->customer->last_name;
                    $address->save();
                }
            }
        });

        $this->info('Columna customer_name actualizada exitosamente.');
    }
}
