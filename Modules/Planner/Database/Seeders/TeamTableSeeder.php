<?php

namespace Modules\Planner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Planner\App\Models\Team;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Team::truncate();
        $data=[
          'Mobile',
          'DevOps',
          'Backend',
          'Front End',
          'Supplier',
          'Back & Front',
          'Supplier & Back',
        ];
        foreach ($data as $team){
            Team::create([
               'title' => $team,
               'owner_id' => 1
            ]);
        }
    }
}
