<?php
namespace Modules\Rank\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Rank\App\Models\Rank;

class RankRepository
{

    public function getTrashedRanks(): \Illuminate\Database\Eloquent\Collection
    {
        return Rank::onlyTrashed()->get();
    }

    public function getRanks(array $filters = [] , $perPage = 15, $paginate = true)
    {
        $query = Rank::query();
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

}
