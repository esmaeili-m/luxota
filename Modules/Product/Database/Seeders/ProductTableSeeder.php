<?php

namespace Modules\Product\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Product\App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();

        $path = database_path('seeders/data/products.csv');

        if (!file_exists($path) || !is_readable($path)) {
            Log::error("CSV file not found or is not readable: {$path}");
            return;
        }

        $file = fopen($path, 'r');

        // Read header
        $headers = fgetcsv($file, 1000, ',');

        if (!$headers) {
            Log::error("CSV header row could not be read.");
            fclose($file);
            return;
        }

        $products = [];
        $counter = 1;
        $lineNumber = 2; // Since header is line 1

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            if (count($row) !== count($headers)) {
                Log::warning("Skipped line $lineNumber: Column count mismatch. Expected " . count($headers) . ", got " . count($row));
                $lineNumber++;
                continue;
            }

            $data = array_combine($headers, $row);

            // Decode title
            $title_data = json_decode($data['title']);
            $title = [
                'en' => $title_data->en ?? '',
                'fa' => $title_data->fa ?? $title_data->en ?? '',
            ];

            // Decode description
            $description_data = json_decode($data['description']);
            $description = [
                'en' => $description_data->en ?? '',
                'fa' => $description_data->fa ?? $description_data->en ?? '',
            ];

            // Handle timestamps
            $createdAt = now();
            $updatedAt = now();

            // Auto-generate or use default values
            $product_code = $data['code'] ?? (1000 + $counter);
            $order = $counter;
            $slug = $data['slug'] ?? Str::slug($title['en']) . '-' . $counter;
            $status = isset($data['status']) ? (bool)$data['status'] : true;
            $show_price = isset($data['show_price']) ? (bool)$data['show_price'] : true;
            $payment_type = isset($data['payment_type']) ? (bool)$data['payment_type'] : true;
            $version = $data['version'] ?? 1.0;
            $last_version_update_date = isset($data['last_version_update_date'])
                ? Carbon::parse($data['last_version_update_date'])
                : $createdAt;

            $products[] = [
                'title' => json_encode($title),
                'description' => json_encode($description),
                'category_id' => $data['category_id'],
                'product_code' => $product_code,
                'last_version_update_date' => $last_version_update_date,
                'version' => $version,
                'image' => $data['thumbnail'] ?? $data['image'] ?? '',
                'video_script' => $data['video_script'] ?? null,
                'slug' => $slug,
                'author_id' => $data['author_id'],
                'order' => $order,
                'show_price' => $show_price,
                'payment_type' => $payment_type,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            $counter++;
            $lineNumber++;
        }

        fclose($file);

        // Insert into DB
        DB::table('products')->insert($products);

    }
}

