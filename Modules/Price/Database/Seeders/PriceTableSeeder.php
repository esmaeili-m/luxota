<?php

namespace Modules\Price\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Price\App\Models\Price;

class PriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Price::truncate();
        $path = database_path('seeders/data/pricings.csv');
        $file = fopen($path, 'r');
        $headers = fgetcsv($file, 1000, ',');
        $prices = [];
        $counter=1;
        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($headers, $row);
            if (!empty($data['created_at'])) {
                try {
                    $createdAt = Carbon::createFromFormat('d/m/Y H:i:s', $data['created_at'])->toDateTimeString();
                } catch (\Exception $e) {
                    $createdAt = now();
                }
            }

            $updatedAt = null;
            if (!empty($data['updated_at'])) {
                try {
                    $updatedAt = Carbon::createFromFormat('d/m/Y H:i:s', $data['updated_at'])->toDateTimeString();
                } catch (\Exception $e) {
                    $updatedAt = now();
                }
            }

            $prices[] = [
                'product_id' => $data['product_id'],
                'price' => $data['price'],
                'zone_id' => $data['zone_id'],
                'created_at' => Carbon::parse($createdAt),
                'updated_at' => Carbon::parse($updatedAt),
            ];
            $counter++;
        }

        fclose($file);
        DB::table('prices')->insert($prices);
    }
}
