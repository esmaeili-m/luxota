<?php
namespace Modules\Zone\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Zone\App\Models\Zone;

class ZoneRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Zone::get();
    }

    public function getTrashedZones(): \Illuminate\Database\Eloquent\Collection
    {
        return Zone::onlyTrashed()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Zone::paginate($perPage);
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

    public function searchByFields(array $filters)
    {
        $query = Zone::query();

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'title':
                    $query->where('title', 'like', "%{$value}%");
                    break;

                case 'description':
                    $query->where('description', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;
            }
        }

        return $query->get();
    }
}
