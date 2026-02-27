<?php

namespace Modules\Planner\Services;

use Modules\Planner\Repositories\TimeEstimateRepository;

class TimeEstimateService
{
    public TimeEstimateRepository $repo;

    public function __construct(TimeEstimateRepository $repo)
    {
        $this->repo =$repo;
    }
    public function syncTimes($data)
    {
        $usersTime = $data['users'];
        $taskId    = $data['task_id'];
        $this->repo->syncTimes($taskId, $usersTime);
    }
}
