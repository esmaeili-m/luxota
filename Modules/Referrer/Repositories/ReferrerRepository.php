<?php
namespace Modules\Referrer\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Referrer\App\Models\Referrer;

class ReferrerRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Referrer::orderBy('title')->get();
    }

    public function getTrashedReferrers(): \Illuminate\Database\Eloquent\Collection
    {
        return Referrer::onlyTrashed()->orderBy('title')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Referrer::orderBy('title')->paginate($perPage);
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

    public function searchByFields(array $filters)
    {
        $query = Referrer::query();

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