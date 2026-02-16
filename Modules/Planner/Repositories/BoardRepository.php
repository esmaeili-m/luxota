<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Board;

class BoardRepository
{
    public function getBoards( $filters,$perPage = 15, $page = 1, $paginate = true)
    {
        $query = Board::query();
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

    public function create($data)
    {
        return Board::create($data);
    }

    public function find(int $id, array $with = ['columns'])
    {
        return Board::with($with)->findOrFail($id);
    }
}
