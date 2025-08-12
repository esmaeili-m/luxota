<?php

namespace Modules\City\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Modules\City\App\Models\City;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        City::truncate();
        $path = database_path('seeders/data/cities.csv');
        $file = fopen($path, 'r');
        $headers = fgetcsv($file, 1000, ',');

        $cities = [];
        $counter=1;
        while (($row = fgetcsv($file, 5000000, ',')) !== false) {
            $data = array_combine($headers, $row);
            $cities[] = [
                'en' => $data['en'],
                'fa' => $data['fa'],
                'ar' => $data['ar'],
                'abb' => $data['abb'] ?? '',
                'country_id' => $data['country_id'],
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
            $counter++;
        }
        fclose($file);
        DB::table('cities')->insert($cities);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
