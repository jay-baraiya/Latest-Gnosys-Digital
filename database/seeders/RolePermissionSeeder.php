<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::where('status', 1)->get();

        foreach ($permissions as $permission) {
            RolePermission::updateOrCreate([
                'role_id' => 1,
                'permission_id' => $permission->id,
            ], [
                'role_id' => 1,
                'permission_id' => $permission->id,
            ]);
        }
    }
}
