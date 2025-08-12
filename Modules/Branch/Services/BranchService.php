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

    public function getPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
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

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
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

    public function toggle_status($id)
    {
        $branch = $this->repo->find($id);
        $newStatus = !$branch->status;
        $this->repo->update($branch, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);
    }
} 