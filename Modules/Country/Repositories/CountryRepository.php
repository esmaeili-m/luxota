<?php

namespace Modules\Country\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Country\App\Models\Country;

class CountryRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Country::orderBy('en')->whereNot('phone_code','')->get();
    }

    public function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Country::where('status', true)->whereNot('phone_code','')->orderBy('en')->get();
    }


    public function getCountries(array $filters = [] , $perPage = 15, $paginate = true)
    {
        $query = Country::query()->with('currency');
        if (!empty($filters)) {
            $query->search($filters);
        }
        if ($paginate) {
            return $query->paginate($perPage);
        } else {

            return $query->get();
        }
    }
    public function find($id): ?Country
    {
        return Country::find($id);
    }

    public function update(Country $country, array $data): Country
    {
        $country->update($data);
        return $country;
    }

    public function getTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Country::onlyTrashed()->paginate($perPage);
    }

    public function findTrashed($id): ?Country
    {
        return Country::onlyTrashed()->find($id);
    }

    public function restore(Country $country): Country
    {
        $country->restore();
        return $country;
    }

    public function forceDelete(Country $country): bool
    {
        return $country->forceDelete();
    }
}
