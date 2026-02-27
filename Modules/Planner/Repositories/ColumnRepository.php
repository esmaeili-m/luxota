<?php

namespace Modules\Planner\Repositories;

class ColumnRepository
{
    public function getNextColumn($task)
    {
        return $task->board->columns()
            ->where('order', '>', $task->column->order)
            ->orderBy('order')
            ->first();
    }

    public function getPreviousColumn($task)
    {
        return $task->board->columns()
            ->where('order', '<', $task->column->order)
            ->orderByDesc('order')
            ->first();
    }

    public function getFirstColumn($board)
    {
        return $board->columns()->orderBy('order')->first();
    }

    public function getLastColumn($board)
    {
        return $board->columns()->orderByDesc('order')->first();
    }

}
