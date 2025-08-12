<?php
namespace Modules\Rank\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Rank\App\Models\Rank;

class RankRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Rank::where('status',1)->get();
    }

    public function getTrashedRanks(): \Illuminate\Database\Eloquent\Collection
    {
        return Rank::onlyTrashed()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Rank::paginate($perPage);
    }

    public function find(int $id, array $with = [])
    {
        return Rank::with($with)->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return Rank::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Rank::create($data);
    }

    public function update(Rank $rank, array $data): bool
    {
        return $rank->update($data);
    }

    public function delete(Rank $rank): bool
    {
        return $rank->delete();
    }

    public function restore(Rank $rank)
    {
        return $rank->restore();
    }

    public function forceDelete($id)
    {
        $rank = Rank::onlyTrashed()->findOrFail($id);
        $rank->forceDelete();
    }

    public function searchByFields(array $filters)
    {
        $query = Rank::query();

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
