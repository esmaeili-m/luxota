<?php

namespace Modules\Country\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Country\App\Models\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Country::truncate();
        $path = database_path('seeders/data/countries.csv');
        $file = fopen($path, 'r');
        $headers = fgetcsv($file, 1000, ',');

        $countries = [];
        $counter=1;
        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($headers, $row);
//            dd($data);
            $countries[] = [
                'en' => $data['en'],
                'fa' => $data['fa'],
                'zone_id' => $data['zone_id']  == '' ? null : $data['zone_id'],
                'abb' => $data['abb'] ?? '',
                'phone_code' => $data['phone_code'],
                'flag' => $data['flag'],
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
            $counter++;
        }
        fclose($file);
        DB::table('countries')->insert($countries);
    }
}
