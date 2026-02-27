<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\TaskLog;

class TaskLogRepository
{
    public function create($data)
    {
        return TaskLog::create($data);
    }

    public function update(TaskLog $taskLog,$data)
    {
        return $taskLog->update($data);
    }

    public function getLastOpenLog(int $taskId, string $taskType)
    {
        return TaskLog::where('task_id', $taskId)
            ->where('task_type', $taskType)
            ->whereNull('ended_at')
            ->orderByDesc('started_at')
            ->first();
    }
    public function closeLog(TaskLog $log): TaskLog
    {
        $log->ended_at = now();
        $log->duration_minutes = now()->diffInMinutes($log->started_at);
        $log->save();

        return $log;
    }
}
