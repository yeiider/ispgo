<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            'Invoice',
            'Customer', // Incluyendo Customer
            'Address',
            'Service',
            'TaxDetail',
            'Router',
            'Plan',
            'User',
            'Role',
            'Permission',
            'Installation',
            'CreditNote',
            'InvoicePayment',
            'PaymentPromise',
            'Ticket',
            'Product',
            'Supplier',
            'Category',
            'Warehouse',
            'CashRegister',
            'Expense',
            'Income',
            'Transaction',
            'TemplateEmail',
            'TemplateHtml',
            'Contract'
        ]);
        Permission::create(['group' => "System Setting", 'name' => 'Setting']);
        $collection->each(function ($item) {
            // Crear permisos para cada modelo
            Permission::create(['group' => $item, 'name' => 'viewAny' . $item]);
            Permission::create(['group' => $item, 'name' => 'view' . $item]);
            Permission::create(['group' => $item, 'name' => 'update' . $item]);
            Permission::create(['group' => $item, 'name' => 'create' . $item]);
            Permission::create(['group' => $item, 'name' => 'delete' . $item]);
            Permission::create(['group' => $item, 'name' => 'destroy' . $item]);
        });
        Permission::create(['group' => "invoice", 'name' => 'PostInvoice']);
        Permission::create(['group' => "invoice", 'name' => 'ViewDailyInvoiceBalance']);



        $role = Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'technician']);
        $role->givePermissionTo(Permission::all());

        $user = User::create([
            "email" => "admin@admin.com",
            "password" => bcrypt('123456'),
            "name" => "Admin",
        ]);

        if ($user) {
            $user->assignRole('super-admin');
        }
    }
}
