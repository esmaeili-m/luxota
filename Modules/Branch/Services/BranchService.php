<?php

namespace Modules\Branch\Services;

use Modules\Branch\App\Models\Branch;
use Modules\Branch\Repositories\BranchRepository;

class BranchService
{
    protected BranchRepository $repo;

    public function __construct(BranchRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getBranches(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'title' => $params['title'] ?? null,
        ];
        $perPage = $params['per_page'] ?? 15;
        $paginate = $params['paginate'] ?? true;
        return $this->repo->getBranches($filters, $perPage, $paginate);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->all();
    }

    public function getTrashedBranches(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedBranches();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Branch
    {
        $branch = $this->repo->find($id);

        if (!$branch) {
            return null;
        }

        $this->repo->update($branch, $data);

        return $branch->fresh();
    }

    public function delete(int $id): bool
    {
        $branch = $this->repo->find($id);
        if (!$branch) {
            return false;
        }
        return $branch->delete();
    }

    public function restoreBranch($id)
    {
        $branch = $this->repo->findTrashedById($id);
        if (!$branch) {
            return false;
        }
        return $this->repo->restore($branch);
    }

    public function forceDeleteBranch($id)
    {
        $this->repo->forceDelete($id);
    }
}
