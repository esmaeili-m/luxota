<?php

namespace Modules\Planner\Services;

use Modules\Planner\Repositories\TeamRepository;

class TeamService
{
    protected TeamRepository $repo;

    public function __construct(TeamRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getTeams(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;
        $paginate = $filters['paginate'] ?? true;

        return $this->repo->getTeams( $perPage, $page, $paginate);
    }
}
