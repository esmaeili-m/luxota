<?php

namespace Modules\Currency\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Currency\App\Models\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Currency::truncate();
        $path = database_path('seeders/data/currencies.csv');
        $file = fopen($path, 'r');
        $headers = fgetcsv($file, 1000, ',');
        $prices = [];
        $counter=1;
        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($headers, $row);
            $prices[] = [
                'title' => $data['title'],
                'status' => $data['status'],
                'abb' => $data['abb'],
                'symbol' => $data['symbol'],
                'decimal_places' => $data['decimal_places'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $counter++;
        }

        fclose($file);
        DB::table('currencies')->insert($prices);
    }
}
