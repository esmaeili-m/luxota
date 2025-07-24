<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user.create',
            'user.update',
            'user.delete',
            'category.create',
            'category.update',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
        }

        $admin = Role::firstOrCreate(['name' => 'superAdmin', 'guard_name' => 'api']);
        $admin->syncPermissions($permissions);
    }
}
