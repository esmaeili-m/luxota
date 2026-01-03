<?php
namespace Modules\Branch\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\App\Models\Branch;

class BranchRepository
{

    public function getTrashedBranches(): \Illuminate\Database\Eloquent\Collection
    {
        return Branch::onlyTrashed()->orderBy('title')->get();
    }

    public function getBranches(array $filters = [] , $perPage = 15, $paginate = true)
    {
        $query = Branch::query();
        if (!empty($filters)) {
            $query->search($filters);
        }
        if ($paginate) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }
    public function find(int $id, array $with = [])
    {
        return Branch::with($with)->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return Branch::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Branch::create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        return $branch->update($data);
    }

    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }

    public function restore(Branch $branch)
    {
        return $branch->restore();
    }

    public function forceDelete($id)
    {
        $branch = Branch::onlyTrashed()->findOrFail($id);
        $branch->forceDelete();
    }

}
