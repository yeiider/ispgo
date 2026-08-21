<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\FrontendPermission;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $perm = FrontendPermission::updateOrCreate(['name' => '/inventory/activity-logs']);

        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->frontendPermissions()->syncWithoutDetaching([$perm->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
