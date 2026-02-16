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
            'user.index', 'user.create', 'user.delete', 'user.restore', 'user.trash', 'user.update',
            'role.index', 'role.create', 'role.delete', 'role.restore', 'role.trash', 'role.update',
            'zone.index', 'zone.create', 'zone.delete', 'zone.restore', 'zone.trash', 'zone.update',
            'rank.index', 'rank.create', 'rank.delete', 'rank.restore', 'rank.trash', 'rank.update',
            'branch.index', 'branch.create', 'branch.delete', 'branch.restore', 'branch.trash', 'branch.update',
            'referrer.index', 'referrer.create', 'referrer.delete', 'referrer.restore', 'referrer.trash', 'referrer.update',
            'category.index', 'category.create', 'category.delete', 'category.restore', 'category.trash', 'category.update', 'category.price',
            'product.index', 'product.create', 'product.delete', 'product.restore', 'product.trash', 'product.update', 'product.price',
            'voucher.index', 'voucher.create', 'voucher.delete', 'voucher.update',        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'api'
            ]);
        }
        $permissionModels = Permission::whereIn('name', $permissions)
            ->where('guard_name', 'api')
            ->get();
        $admin = Role::firstOrCreate([
            'name' => 'SuperAdmin',
            'guard_name' => 'api'
        ]);
        $admin->syncPermissions($permissionModels);
        $user = User::find(1);
        $user->syncRoles([$admin]);
        $permissions = $user->getAllPermissions();
    }
}
