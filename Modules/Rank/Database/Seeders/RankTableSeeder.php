<?php

namespace Modules\Rank\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Rank\App\Models\Rank;

class RankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
         Rank::truncate();
         $data=[
            ['title'=>'Agency Without Website','status'=>1],
            ['title'=>'Agency Wit Static Website','status'=>1],
            ['title'=>'Agency With Basic Booking Engine','status'=>1],
            ['title'=>'Agency With Advanced Booking Engine','status'=>1],
         ];
         Rank::insert($data);
    }
}
