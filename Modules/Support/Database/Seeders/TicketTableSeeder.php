<?php

namespace Modules\Support\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $path = database_path('seeders/data/threads.csv');
        $file = fopen($path, 'r');
        $headers = fgetcsv($file, 1000, ',');
        $status=[
          'open',
          'solved',
          'waiting review',
          'dev progress',
          'waiting customer res',
        ];
        $threads = [];
        $counter=1;
        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($headers, $row);
            $threads[] = [
                'user_id' => $data['agency_id'],
                'subject' => $data['title'],
                'code' => $data['code'],
                'last_reply_at' => null,
                'status' => $status[$data['is_closed']] ?? 'open',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $counter++;
        }

        fclose($file);
        DB::table('tickets')->insert($threads);
        // $this->call([]);
    }
}
