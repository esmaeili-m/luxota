<?php

namespace Modules\Rank\Services;

use Modules\Rank\App\Models\Rank;
use Modules\Rank\Repositories\RankRepository;

class RankService
{
    protected RankRepository $repo;

    public function __construct(RankRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getRanks(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'title' => $params['title'] ?? null,
        ];
        $perPage = $params['per_page'] ?? 15;
        $paginate = $params['paginate'] ?? true;
        return $this->repo->getRanks($filters, $perPage, $paginate);
    }

    public function getTrashedRanks(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedRanks();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Rank
    {
        $rank = $this->repo->find($id);

        if (!$rank) {
            return null;
        }

        $this->repo->update($rank, $data);

        return $rank->fresh();
    }

    public function delete(int $id): bool
    {
        $rank = $this->repo->find($id);
        if (!$rank) {
            return false;
        }
        return $rank->delete();
    }

    public function restoreRank($id)
    {
        $rank = $this->repo->findTrashedById($id);
        if (!$rank) {
            return false;
        }
        return $this->repo->restore($rank);
    }

    public function forceDeleteRank($id)
    {
        $this->repo->forceDelete($id);
    }
}
