<?php
namespace Modules\Referrer\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Referrer\App\Models\Referrer;

class ReferrerRepository
{
    public function getTrashedReferrers(): \Illuminate\Database\Eloquent\Collection
    {
        return Referrer::onlyTrashed()->orderBy('title')->get();
    }

    public function getReferrers(array $filters = [] , $perPage = 15, $paginate = true)
    {
        $query = Referrer::query();
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
        return Referrer::with($with)->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return Referrer::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Referrer::create($data);
    }

    public function update(Referrer $referrer, array $data): bool
    {
        return $referrer->update($data);
    }

    public function delete(Referrer $referrer): bool
    {
        return $referrer->delete();
    }

    public function restore(Referrer $referrer)
    {
        return $referrer->restore();
    }

    public function forceDelete($id)
    {
        $referrer = Referrer::onlyTrashed()->findOrFail($id);
        $referrer->forceDelete();
    }

}
