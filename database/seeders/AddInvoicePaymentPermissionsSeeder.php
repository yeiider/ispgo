<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddInvoicePaymentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $resource = 'InvoicePayment';

        // Check if permissions already exist
        $existingPermissions = Permission::where('group', $resource)->count();
        
        if ($existingPermissions > 0) {
            $this->command->info("Permissions for {$resource} already exist. Skipping...");
            return;
        }

        // Create permissions for InvoicePayment
        $permissions = [
            Permission::create(['group' => $resource, 'name' => 'viewAny' . $resource]),
            Permission::create(['group' => $resource, 'name' => 'view' . $resource]),
            Permission::create(['group' => $resource, 'name' => 'update' . $resource]),
            Permission::create(['group' => $resource, 'name' => 'create' . $resource]),
            Permission::create(['group' => $resource, 'name' => 'delete' . $resource]),
            Permission::create(['group' => $resource, 'name' => 'destroy' . $resource]),
        ];

        $this->command->info("Created " . count($permissions) . " permissions for {$resource}");

        // Assign all permissions to super-admin role
        $superAdminRole = Role::where('name', 'super-admin')->first();
        
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
            $this->command->info("Assigned permissions to super-admin role");
        }

        $this->command->info("✅ InvoicePayment permissions created successfully!");
    }
}
