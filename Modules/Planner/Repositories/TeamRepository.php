<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Team;

class TeamRepository
{
    public function getTeams( $perPage = 15, $page = 1, $paginate = true)
    {
        $query = Team::query();
        return $paginate
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }
}
