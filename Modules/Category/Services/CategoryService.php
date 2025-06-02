<?php

namespace Modules\Category\Services;

use Modules\Category\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $repo;

    public function __construct(CategoryRepository $repo)
    {
    $this->repo = $repo;
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
    return $this->repo->all();
    }

    public function getById($id)
    {
    return $this->repo->find($id);
    }

    public function create(array $data)
    {
    return $this->repo->create($data);
    }

    public function update($id, array $data)
    {
    return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
    return $this->repo->delete($id);
    }
}
