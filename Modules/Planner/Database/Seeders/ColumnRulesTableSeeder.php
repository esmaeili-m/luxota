<?php

namespace Modules\Planner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Planner\App\Models\Column;
use Modules\Planner\App\Models\ColumnRule;

class ColumnRulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ColumnRule::truncate();
        $columns=Column::all();
        foreach ($columns ?? [] as $item){
            ColumnRule::create([
                'column_id'=>$item->id,
                'track_time'=>true,
                'max_tasks_per_user'=>100,
                'rules_json'=>[
                    'allow_forward'=>1,
                    'allow_backward'=>1,
                    'show_history'=>1,
                    'show_subtasks'=>1,
                    'show_elapsed_from_created'=>1,
                    'time_in_column'=>1,
                    'progress_bar'=>1,
                    'task_code'=>1,
                    'task_story'=>1,
                    'crm_code'=>1,
                    'task_category'=>1,
                    'team'=>1,
                    'note'=>1,
                    'order'=>1,
                ],
            ]);

        }
    }
}
