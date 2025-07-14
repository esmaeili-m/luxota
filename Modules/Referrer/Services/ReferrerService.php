<?php

namespace Modules\Referrer\Services;

use Modules\Referrer\App\Models\Referrer;
use Modules\Referrer\Repositories\ReferrerRepository;

class ReferrerService
{
    protected ReferrerRepository $repo;

    public function __construct(ReferrerRepository $repo)
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

    public function getTrashedReferrers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedReferrers();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Referrer
    {
        $referrer = $this->repo->find($id);

        if (!$referrer) {
            return null;
        }

        $this->repo->update($referrer, $data);

        return $referrer->fresh();
    }

    public function delete(int $id): bool
    {
        $referrer = $this->repo->find($id);
        if (!$referrer) {
            return false;
        }
        return $referrer->delete();
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreReferrer($id)
    {
        $referrer = $this->repo->findTrashedById($id);
        if (!$referrer) {
            return false;
        }
        return $this->repo->restore($referrer);
    }

    public function forceDeleteReferrer($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $referrer = $this->repo->find($id);
        $newStatus = !$referrer->status;
        $this->repo->update($referrer, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);
    }
} 