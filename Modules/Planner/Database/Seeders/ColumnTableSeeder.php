<?php

namespace Modules\Planner\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Planner\App\Models\Board;
use Modules\Planner\App\Models\Column;

class ColumnTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Column::truncate();
        $structure = [
            'product' => [
                ['name' => 'Backlog',       'key' => 'backlog',     'is_start' => true],
                ['name' => 'Discovery',     'key' => 'discovery'],
                ['name' => 'Ready for Dev', 'key' => 'ready_dev'],
                ['name' => 'In Progress',   'key' => 'in_progress', 'wip_limit' => 5],
                ['name' => 'Done',          'key' => 'done',        'is_end' => true],
            ],

            'dev' => [
                ['name' => 'Backlog',     'key' => 'backlog', 'is_start' => true],
                ['name' => 'Selected',    'key' => 'selected'],
                ['name' => 'In Progress', 'key' => 'in_progress', 'wip_limit' => 3],
                ['name' => 'Code Review', 'key' => 'review', 'wip_limit' => 2],
                ['name' => 'Merged',      'key' => 'merged'],
                ['name' => 'Done',        'key' => 'done', 'is_end' => true],
            ],

            'qa' => [
                ['name' => 'Ready for Test', 'key' => 'ready_test', 'is_start' => true],
                ['name' => 'Testing',        'key' => 'testing', 'wip_limit' => 4],
                ['name' => 'Bug Found',      'key' => 'bug'],
                ['name' => 'Re-Test',        'key' => 'retest'],
                ['name' => 'Approved',       'key' => 'approved', 'is_end' => true],
            ],

            'delivery' => [
                ['name' => 'Ready for Release', 'key' => 'ready_release', 'is_start' => true],
                ['name' => 'Staging',           'key' => 'staging'],
                ['name' => 'UAT',               'key' => 'uat'],
                ['name' => 'Production',        'key' => 'production', 'is_end' => true],
            ],
        ];

        $boards = Board::all();

        foreach ($boards as $board) {

            if (!isset($structure[$board->key])) {
                continue;
            }

            // اگر قبلاً ستون داشت رد کن
            if ($board->columns()->exists()) {
                continue;
            }

            foreach ($structure[$board->key] as $index => $column) {

                $board->columns()->create([
                    'title'        => $column['name'],
                    'key'         => $column['key'] ?? null,
                    'order'       => $index + 1,
                    'description' => null,
                    'wip_limit'   => $column['wip_limit'] ?? null,
                    'is_start'    => $column['is_start'] ?? false,
                    'is_end'      => $column['is_end'] ?? false,
                    'status'   => true,
                ]);
            }
        }
    }
}
