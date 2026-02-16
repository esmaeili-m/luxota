<?php

namespace Modules\Planner\Services;

use Modules\Planner\App\Models\Sprint;
use Modules\Planner\Repositories\SprintRepository;

class SprintService
{
    protected SprintRepository $repo;

    public function __construct(SprintRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getSprints(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;
        $paginate = $filters['paginate'] ?? true;

        return $this->repo->getSprints( $perPage, $page, $paginate);
    }

    public function create($data)
    {
        $data['created_by']=auth()->user()->id;
        return $this->repo->create($data);
    }
}
