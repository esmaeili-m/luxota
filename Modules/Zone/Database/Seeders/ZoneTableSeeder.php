<?php

namespace Modules\Zone\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Zone\App\Models\Zone;

class ZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Zone::truncate();
        $zones = [
            [
                'title' => 'North Zone',
                'description' => 'Northern region of the city',
                'status' => true
            ],
            [
                'title' => 'South Zone',
                'description' => 'Southern region of the city',
                'status' => true
            ],
            [
                'title' => 'East Zone',
                'description' => 'Eastern region of the city',
                'status' => true
            ],
            [
                'title' => 'West Zone',
                'description' => 'Western region of the city',
                'status' => true
            ],
            [
                'title' => 'Central Zone',
                'description' => 'Central business district',
                'status' => true
            ],
        ];
        Zone::insert($zones);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
} 