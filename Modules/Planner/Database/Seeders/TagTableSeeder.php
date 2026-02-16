<?php

namespace Modules\Planner\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Planner\App\Models\Tag;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Tag::truncate();
        $path = database_path('seeders/data/planner_tasktags.csv');
        $file = fopen($path, 'r');

        $headers = fgetcsv($file, 1000, ',');

        $categories = [];
        $counter=1;
        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($headers, $row);
            $categories[] = [
                'title' => $data['title'],
                'color' =>$data['color'],
                'parent_id' =>$data['parent_id']  == '' ? null : $data['parent_id'],
                'created_at' => now(),
                'updated_at' =>  now(),
            ];
            $counter++;
        }

        fclose($file);
        DB::table('tags')->insert($categories);
    }

}
