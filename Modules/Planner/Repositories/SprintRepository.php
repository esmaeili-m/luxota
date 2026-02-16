<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Sprint;

class SprintRepository
{
    public function getSprints( $perPage = 15, $page = 1, $paginate = true)
    {
        $query = Sprint::query();
        return $paginate
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }

    public function create($data)
    {
        return Sprint::create($data);
    }
}
