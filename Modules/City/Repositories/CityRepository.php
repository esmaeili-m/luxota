<?php
namespace Modules\City\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\City\App\Models\City;

class CityRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return City::with('country')->orderBy('en')->get();
    }

    public function getTrashedCities(): \Illuminate\Database\Eloquent\Collection
    {
        return City::with('country')->onlyTrashed()->orderBy('en')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return City::with('country')->orderBy('en')->paginate($perPage);
    }

    public function find(int $id, array $with = [])
    {
        return City::with($with)->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return City::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return City::create($data);
    }

    public function update(City $city, array $data): bool
    {
        return $city->update($data);
    }

    public function delete(City $city): bool
    {
        return $city->delete();
    }

    public function restore(City $city)
    {
        return $city->restore();
    }

    public function forceDelete($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->forceDelete();
    }

    public function searchByFields(array $filters)
    {
        $query = City::with('country');

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'en':
                    $query->where('en', 'like', "%{$value}%");
                    break;

                case 'abb':
                    $query->where('abb', 'like', "%{$value}%");
                    break;

                case 'country_id':
                    $query->where('country_id', $value);
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;

                case 'priority':
                    $query->where('priority', $value);
                    break;
            }
        }

        return $query->orderBy('en')->get();
    }
}
