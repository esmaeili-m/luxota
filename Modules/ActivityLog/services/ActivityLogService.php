<?php

namespace Modules\ActivityLog\services;

use Modules\ActivityLog\Repositories\ActivityLogRepository;

class ActivityLogService
{
    public ActivityLogRepository $repo;
    public function __construct(ActivityLogRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getAll()
    {
        return $this->repo->getAll();
    }
}
