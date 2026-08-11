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
        Permission::firstOrCreate(['name' => 'Setting', 'guard_name' => 'web'], ['group' => "System Setting"]);
        $collection->each(function ($item) {
            // Crear permisos para cada modelo
            Permission::firstOrCreate(['name' => 'viewAny' . $item, 'guard_name' => 'web'], ['group' => $item]);
            Permission::firstOrCreate(['name' => 'view' . $item, 'guard_name' => 'web'], ['group' => $item]);
            Permission::firstOrCreate(['name' => 'update' . $item, 'guard_name' => 'web'], ['group' => $item]);
            Permission::firstOrCreate(['name' => 'create' . $item, 'guard_name' => 'web'], ['group' => $item]);
            Permission::firstOrCreate(['name' => 'delete' . $item, 'guard_name' => 'web'], ['group' => $item]);
            Permission::firstOrCreate(['name' => 'destroy' . $item, 'guard_name' => 'web'], ['group' => $item]);
        });
        Permission::firstOrCreate(['name' => 'PostInvoice', 'guard_name' => 'web'], ['group' => "invoice"]);
        Permission::firstOrCreate(['name' => 'ViewDailyInvoiceBalance', 'guard_name' => 'web'], ['group' => "invoice"]);



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
