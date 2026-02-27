<?php

namespace Modules\Planner\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Planner\App\Models\Task;
use Modules\Planner\Repositories\TaskLogRepository;
use Modules\Planner\Repositories\TaskRepository;

class TaskLogService
{
    public TaskLogRepository $repo;
    public TaskRepository $taskRepo;
    public function __construct(TaskLogRepository $repo,TaskRepository $taskRepo)
    {
        $this->repo=$repo;
        $this->taskRepo=$taskRepo;
    }

    public function createTaskLog(Task $task, string $action, array $extra = [])
    {
        $note = $extra['note'] ?? ucfirst($action) . " in board '{$task->board->name}' and column '{$task->column->name}'";

        $logData = [
            'task_type'       => 'task',
            'task_id'         => $task->id,
            'from_board_id'   => $task->board_id,
            'from_column_id'  => $task->column_id,
            'to_board_id'     => $task->board_id,
            'to_column_id'    => $task->column_id,
            'user_id'         => Auth::id(),
            'started_at'      => now(),
            'ended_at'        => null,
            'duration_minutes'=> null,
            'note'            => $note,
        ];

        return $this->repo->create($logData);
    }
    public function moveTaskLog(
        Task $task,
        $fromBoard,
        $fromColumn,
        array $extra = []
    ) {
        $lastLog = $this->repo->getLastOpenLog($task->id, 'task');
        if ($lastLog) {
            $log['ended_at'] = now();
            $log['duration_minutes'] = now()->diffInMinutes($lastLog->started_at);
            $this->repo->update($lastLog,$log);
            if ($fromColumn?->rule && $fromColumn?->rule?->track_time){
                $total_tracked_minutes =$log['duration_minutes']+$task->total_tracked_minutes;
                $this->taskRepo->update($task,['total_tracked_minutes'=>$total_tracked_minutes]);
            }
        }
        $task->load('board', 'column');

        $note = $extra['note'] ??
            "Moved from Board {$fromBoard->title}/Column {$fromColumn->title}
         to 'Board {$task->board->title}/Column {$task->column->title}'";

        $logData = [
            'task_type'       => 'task',
            'task_id'         => $task->id,
            'from_board_id'   => $fromBoard->id,
            'from_column_id'  => $fromColumn->id,
            'to_board_id'     => $task->board_id,
            'to_column_id'    => $task->column_id,
            'user_id'         => Auth::id(),
            'started_at'      => now(),
            'ended_at'        => null,
            'duration_minutes'=> null,
            'note'            => $note,
        ];

        return $this->repo->create($logData);
    }


    public function update($data)
    {

    }
}
