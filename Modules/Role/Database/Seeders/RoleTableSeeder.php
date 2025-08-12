<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Role\App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \Spatie\Permission\Models\Role::truncate();
        $roles = [
            ['name' => 'Customer','guard_name' => 'api'],
            ['name' => 'SuperAdmin','guard_name' => 'api'],
        ];
        Role::insert($roles);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
