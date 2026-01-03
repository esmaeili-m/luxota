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

    public function getZones(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'title' => $params['title'] ?? null,
            'description' => $params['description'] ?? null,
        ];
        $perPage = $params['per_page'] ?? 15;
        $paginate = $params['paginate'] ?? true;
        return $this->repo->getZones($filters, $perPage, $paginate);
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

}
