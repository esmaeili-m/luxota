<?php

namespace Modules\Planner\Database\Seeders;

use Illuminate\Database\Seeder;

class PlannerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             TagTableSeeder::class,
             BoardTableSeeder::class,
             ColumnTableSeeder::class,
             TeamTableSeeder::class,
         ]);
    }
}
