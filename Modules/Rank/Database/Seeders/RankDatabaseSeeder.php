<?php

namespace Modules\Rank\Database\Seeders;

use Illuminate\Database\Seeder;

class RankDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([RankTableSeeder::class]);
    }
}
