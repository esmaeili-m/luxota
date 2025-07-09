<?php

namespace Modules\Category\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Category\App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
         Category::truncate();
        $path = database_path('seeders/data/categories.csv');
        $file = fopen($path, 'r');

        // اولین خط (هدر) رو می‌خونیم
        $headers = fgetcsv($file, 1000, ',');

        $categories = [];
        $counter=1;
        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $data = array_combine($headers, $row);
            $title_data  =json_decode($data['title']);
            $title = [
                'en' => $title_data->en ?? '',
            ];
            $subtitle_data  =json_decode($data['subtitle']);
            $subtitle = [
                'en' => $subtitle_data->en ?? '',
            ];

            $parentId = $data['parent_id'] === '' ? null : intval($data['parent_id']);

            $status = ($data['status'] ?? 1) === '1' ? 1 : 0;
            $createdAt = null;
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

            $categories[] = [
                'title' => json_encode($title),
                'subtitle' => json_encode($subtitle),
                'slug' => $data['slug'],
                'image' => $data['image'] ?? '',
                'status' => 1,
                'order' => $counter,
                'parent_id' => $parentId,
                'created_at' => Carbon::parse($createdAt),
                'updated_at' => Carbon::parse($updatedAt),
            ];
            $counter++;
        }

        fclose($file);
        DB::table('categories')->insert($categories);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
