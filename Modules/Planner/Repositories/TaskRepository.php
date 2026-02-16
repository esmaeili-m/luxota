<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Task;

class TaskRepository
{
    public function getTasks(array $filters = [], $perPage = 15, $page = 1, $paginate = true)
    {
        $query = Task::query();
        if (isset($filters['with'])) {
            $query->with([$filters['with']]);
        }
        if (!empty($filters)) {
            $query->search($filters);
        }

        return $paginate
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }
}
