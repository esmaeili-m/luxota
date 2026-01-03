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
                'title' => 'Gulf Area',
                'description' => 'Gulf Area',
                'status' => true
            ],
            [
                'title' => 'Iran',
                'description' => 'Iran',
                'status' => true
            ],
            [
                'title' => 'Levant area',
                'description' => 'Levant area',
                'status' => true
            ],
            [
                'title' => 'Bahrain & KSA',
                'description' => 'Bahrain & KSA',
                'status' => true
            ],
        ];
        Zone::insert($zones);
    }
}
