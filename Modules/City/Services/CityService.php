<?php

namespace Modules\City\Services;

use Modules\City\App\Models\City;
use Modules\City\Repositories\CityRepository;

class CityService
{
    protected CityRepository $repo;

    public function __construct(CityRepository $repo)
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

    public function getTrashedCities(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedCities();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?City
    {
        $city = $this->repo->find($id);

        if (!$city) {
            return null;
        }

        $this->repo->update($city, $data);

        return $city->fresh();
    }

    public function delete(int $id): bool
    {
        $city = $this->repo->find($id);
        if (!$city) {
            return false;
        }
        return $city->delete();
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreCity($id)
    {
        $city = $this->repo->findTrashedById($id);
        if (!$city) {
            return false;
        }
        return $this->repo->restore($city);
    }

    public function forceDeleteCity($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $city = $this->repo->find($id);
        $newStatus = !$city->status;
        $this->repo->update($city, ['status' => $newStatus]);
        return response()->json(['message' => 'Change Status successfully']);
    }
} 