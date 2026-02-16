<?php

namespace Modules\Planner\Services;

use Modules\Planner\Repositories\TaskRepository;

class TaskService
{
    protected TaskRepository $repo;

    public function __construct(TaskRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getTasks(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;
        $paginate = $filters['paginate'] ?? true;

        return $this->repo->getTasks($filters, $perPage, $page, $paginate);
    }
}
