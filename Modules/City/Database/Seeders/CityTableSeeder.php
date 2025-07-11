<?php

namespace Modules\City\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/cities.csv');

        if (!file_exists($path)) {
            $this->command->error("File not found: $path");
            return;
        }

        $this->command->info("Reading file: $path");

        // بارگذاری فایل CSV
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // اولین ردیف رو به عنوان هدر می‌شناسه

        $batchSize = 500;
        $records = [];
        $count = 0;

        // خواندن رکوردها به صورت iterator
        foreach ($csv->getRecords() as $record) {
            // $record یک آرایه associate است با کل ستون‌ها
            // فقط در صورت لزوم می‌توانی پردازش انجام بدی، در غیر اینصورت مستقیم ذخیره کن
            $records[] = $record;

            if (count($records) === $batchSize) {
                DB::table('cities')->insert($records);
                $count += count($records);
                $this->command->info("Inserted $count records...");
                $records = [];
            }
        }

        if (!empty($records)) {
            DB::table('cities')->insert($records);
            $count += count($records);
            $this->command->info("Inserted $count records...");
        }

        $this->command->info("✅ Import completed. Total inserted: $count");
    }
}
