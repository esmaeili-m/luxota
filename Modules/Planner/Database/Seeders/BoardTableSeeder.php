<?php

namespace Modules\Planner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Planner\App\Models\Board;

class BoardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Board::truncate();
        $boards=[
          [
              'title'=>'Product',
              'key'=>'product',
              'type'=>'kanban',
              'owner_type'=>'team',
              'owner_id'=>1,
              'visibility'=>'public',
              'created_by'=>1,
          ],
            [
              'title'=>'Developer',
              'key'=>'dev',
              'type'=>'kanban',
              'owner_type'=>'team',
              'owner_id'=>1,
              'visibility'=>'public',
              'created_by'=>1,
          ],
            [
              'title'=>'Qa',
              'key'=>'qa',
              'type'=>'kanban',
              'owner_type'=>'team',
              'owner_id'=>1,
              'visibility'=>'public',
              'created_by'=>1,
          ],
            [
              'title'=>'Delivery',
              'key'=>'delivary',
              'type'=>'kanban',
              'owner_type'=>'team',
              'owner_id'=>1,
              'visibility'=>'public',
              'created_by'=>1,
          ],
        ];
        foreach ($boards as $board){
            Board::create($board);
        }
    }
}
