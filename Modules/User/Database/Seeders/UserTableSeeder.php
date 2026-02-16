<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::Truncate();
        User::create([
            'name' => 'Mahdi Esmaeili',
            'email' => 'mahdi@gmail.com',
            'password' => bcrypt('mahdicfc'),
            'phone' => '09193544391',
            'description' => 'System Administrator',
            'status' => true,
            'country_code' => '+98',
            'whatsapp_number' => '9123456789',
            'role_id' => 2,
            'zone_id' => 1,
            'city_id' => 8878,
            'rank_id' => 1,
            'referrer_id' => 1,
            'branch_id' => 1,
            'parent_id' => null,
        ]);
    }
}
