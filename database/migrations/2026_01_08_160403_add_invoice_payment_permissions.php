<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if permissions already exist
        $existingCount = DB::table('permissions')
            ->where('group', 'InvoicePayment')
            ->count();

        // Only insert permissions if they don't exist
        if ($existingCount === 0) {
            $permissions = [
                ['group' => 'InvoicePayment', 'name' => 'viewAnyInvoicePayment', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'InvoicePayment', 'name' => 'viewInvoicePayment', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'InvoicePayment', 'name' => 'updateInvoicePayment', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'InvoicePayment', 'name' => 'createInvoicePayment', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'InvoicePayment', 'name' => 'deleteInvoicePayment', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['group' => 'InvoicePayment', 'name' => 'destroyInvoicePayment', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ];

            DB::table('permissions')->insert($permissions);
        }

        // Assign permissions to super-admin role
        $superAdminRole = DB::table('roles')->where('name', 'super-admin')->first();
        
        if ($superAdminRole) {
            $permissionIds = DB::table('permissions')
                ->where('group', 'InvoicePayment')
                ->pluck('id');
            
            $roleHasPermissions = [];
            foreach ($permissionIds as $permissionId) {
                // Check if this permission is already assigned to the role
                $exists = DB::table('role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $superAdminRole->id)
                    ->exists();
                
                if (!$exists) {
                    $roleHasPermissions[] = [
                        'permission_id' => $permissionId,
                        'role_id' => $superAdminRole->id,
                    ];
                }
            }
            
            // Only insert if there are new permissions to assign
            if (!empty($roleHasPermissions)) {
                DB::table('role_has_permissions')->insert($roleHasPermissions);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete role_has_permissions entries
        $permissionIds = DB::table('permissions')
            ->where('group', 'InvoicePayment')
            ->pluck('id');
        
        DB::table('role_has_permissions')
            ->whereIn('permission_id', $permissionIds)
            ->delete();

        // Delete permissions
        DB::table('permissions')
            ->where('group', 'InvoicePayment')
            ->delete();
    }
};
