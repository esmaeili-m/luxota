<?php

namespace Modules\ActivityLog\Repositories;

use Modules\ActivityLog\App\Models\ActivityLog;

class ActivityLogRepository
{

    public function getAll()
    {
        return ActivityLog::latest()->with(['user'])->paginate(30);
    }
}
