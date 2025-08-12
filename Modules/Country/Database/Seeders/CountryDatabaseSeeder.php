<?php

namespace Modules\Country\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Database\Seeders\CategoryTableSeeder;
use Modules\Country\App\Models\Country;

class CountryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([CountryTableSeeder::class]);


    }
}
