<?php
namespace Modules\Branch\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\App\Models\Branch;

class BranchRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Branch::orderBy('title')->get();
    }

    public function getTrashedBranches(): \Illuminate\Database\Eloquent\Collection
    {
        return Branch::onlyTrashed()->orderBy('title')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Branch::orderBy('title')->paginate($perPage);
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

    public function searchByFields(array $filters)
    {
        $query = Branch::query();

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'title':
                    $query->where('title', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query->get();
    }
} 