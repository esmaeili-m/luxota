<?php

namespace Modules\User\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@luxota.com',
            'password' => bcrypt('password'),
            'phone' => '+989123456789',
            'description' => 'System Administrator',
            'status' => true,
            'country_code' => 'IR',
            'whatsapp_number' => '+989123456789',
            'role_id' => 1,
            'zone_id' => 1,
            'city_id' => 1,
            'rank_id' => 1,
            'referrer_id' => 1,
            'branch_id' => 1,
            'parent_id' => null,
        ]);

        // Create sample users
        User::factory(10)->create();
    }
} 