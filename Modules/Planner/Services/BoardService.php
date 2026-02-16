<?php

namespace Modules\Planner\Services;

use Modules\Planner\Repositories\BoardRepository;

class BoardService
{
    protected BoardRepository $repo;

    public function __construct(BoardRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getBoards(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;
        $paginate = $filters['paginate'] ?? true;

        return $this->repo->getBoards($filters, $perPage, $page, $paginate);
    }

    public function create($data)
    {
        return $this->repo->create($data);
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }
}
