<?php

use App\Models\FrontendPermission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $perm = FrontendPermission::updateOrCreate(['name' => '/users/action-logs']);

        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->frontendPermissions()->syncWithoutDetaching([$perm->id]);
        }
    }

    public function down(): void
    {
        FrontendPermission::where('name', '/users/action-logs')->delete();
    }
};
