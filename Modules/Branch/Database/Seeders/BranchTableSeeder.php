<?php

namespace Modules\Branch\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Branch\App\Models\Branch;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Branch::truncate();
        $data=[
            ['title'=>'ATM 2024','status'=>1],
            ['title'=>'ATM 2023','status'=>1],
            ['title'=>'Luxota Site','status'=>1],
            ['title'=>'Yellow Page','status'=>1],
            ['title'=>'Google Map','status'=>1],
            ['title'=>'Mr. Abu Roghaye-Fly4all','status'=>1],
            ['title'=>'Matin International','status'=>1],
            ['title'=>'Partners','status'=>1],
            ['title'=>'Mr. Husain Fahad','status'=>1],
            ['title'=>'Nigeria Chamber of Commerce','status'=>1],
            ['title'=>'Masud Ahmadi-Shadshargh','status'=>1],
        ];
        Branch::insert($data);
    }
}
