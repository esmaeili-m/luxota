<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\TimeEstimate;

class TimeEstimateRepository
{
    public function syncTimes(int $taskId, array $usersTime): void
    {
        foreach ($usersTime as $userId => $time) {

            if ($time === null || $time === '') {
                TimeEstimate::where('task_id', $taskId)
                    ->where('user_id', $userId)
                    ->delete();
                continue;
            }

            TimeEstimate::updateOrCreate(
                [
                    'task_id' => $taskId,
                    'user_id' => $userId,
                ],
                [
                    'estimated_minutes' => $time,
                ]
            );
        }
    }
}
