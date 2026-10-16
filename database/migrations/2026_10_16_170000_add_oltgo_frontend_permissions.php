<?php

use App\Models\FrontendPermission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private array $paths = ['/olts', '/olts/settings'];

    public function up(): void
    {
        $superAdmin = Role::where('name', 'super-admin')->first();

        foreach ($this->paths as $path) {
            $perm = FrontendPermission::updateOrCreate(['name' => $path]);
            $superAdmin?->frontendPermissions()->syncWithoutDetaching([$perm->id]);
        }
    }

    public function down(): void
    {
        FrontendPermission::whereIn('name', $this->paths)->delete();
    }
};
