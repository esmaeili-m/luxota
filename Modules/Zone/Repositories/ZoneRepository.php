<?php
namespace Modules\Zone\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Zone\App\Models\Zone;

class ZoneRepository
{

    public function getTrashedZones(): \Illuminate\Database\Eloquent\Collection
    {
        return Zone::onlyTrashed()->get();
    }

    public function getZones(array $filters = [] , $perPage = 15, $paginate = true)
    {
        $query = Zone::query();
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
        return Zone::with($with)->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return Zone::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Zone::create($data);
    }

    public function update(Zone $zone, array $data): bool
    {
        return $zone->update($data);
    }

    public function delete(Zone $zone): bool
    {
        return $zone->delete();
    }

    public function restore(Zone $zone)
    {
        return $zone->restore();
    }

    public function forceDelete($id)
    {
        $zone = Zone::onlyTrashed()->findOrFail($id);
        $zone->forceDelete();
    }

}
