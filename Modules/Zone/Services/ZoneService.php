<?php

namespace Modules\Zone\Services;

use Modules\Zone\App\Models\Zone;
use Modules\Zone\Repositories\ZoneRepository;

class ZoneService
{
    protected ZoneRepository $repo;

    public function __construct(ZoneRepository $repo)
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

    public function getTrashedZones(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedZones();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Zone
    {
        $zone = $this->repo->find($id);

        if (!$zone) {
            return null;
        }

        $this->repo->update($zone, $data);

        return $zone->fresh();
    }

    public function delete(int $id): bool
    {
        $zone = $this->repo->find($id);
        if (!$zone) {
            return false;
        }
        return $zone->delete();
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreZone($id)
    {
        $zone = $this->repo->findTrashedById($id);
        if (!$zone) {
            return false;
        }
        return $this->repo->restore($zone);
    }

    public function forceDeleteZone($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $zone = $this->repo->find($id);
        $newStatus = !$zone->status;
        $this->repo->update($zone, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);
    }
} 