<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\App\Models\User;
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
            //Users
            'user.index',
            'user.create',
            'user.delete',
            'user.restore',
            'user.trash',
            'user.update',

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
        }
        $permissionModels = Permission::whereIn('name', $permissions)
            ->where('guard_name', 'api')
            ->get();
        $admin = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'api']);

        $admin->syncPermissions($permissionModels);
        $user = User::find(2);

        $permissions = $user->getAllPermissions();

        dd($permissions->pluck('name'));

    }
}
