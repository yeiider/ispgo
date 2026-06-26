<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FrontendPermission;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert initial frontend permissions
        $routes = [
            '/clients',
            '/services',
            '/billing/invoices',
            '/billing/novedades',
            '/billing/rules',
            '/billing/reports',
            '/finance/expenses',
            '/finance/suppliers',
            '/finance/expense-categories',
            '/inventory/products',
            '/inventory/warehouses',
            '/inventory/categories',
            '/inventory/low-stock',
            '/marketing/cotizaciones',
            '/tickets',
            '/users',
            '/users/roles',
            '/routers',
            '/plans',
            '/plans/additional',
            '/nap-boxes',
            '/nap-boxes/cartography',
            '/cash-registers',
            '/cash-registers/closures',
            '/cash-registers/report',
            '/collection-point',
            '/settings',
            '/smart-olt',
        ];

        foreach ($routes as $route) {
            FrontendPermission::updateOrCreate(['name' => $route]);
        }

        // Assign all permissions to super-admin role
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $allPerms = FrontendPermission::all();
            $superAdmin->frontendPermissions()->sync($allPerms->pluck('id'));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertiremos la inserción de datos en el down para evitar inconsistencias
        // si se han agregado más permisos manualmente.
    }
};
