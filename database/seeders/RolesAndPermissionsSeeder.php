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
            'InternetPlan',
            'User',
            'Role',
            'Permission',
            'Installation'
            // Otros modelos que necesiten permisos
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

        // Crear un rol Super-Admin y asignar todos los permisos


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
