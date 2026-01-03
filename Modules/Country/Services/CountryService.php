<?php

namespace Modules\Country\Services;

use Modules\Country\Repositories\CountryRepository;

class CountryService
{
    protected CountryRepository $repo;

    public function __construct(CountryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function update($id,$data)
    {
        $country = $this->repo->find($id);

        if (!$country) {
            return null;
        }

        $this->repo->update($country, $data);

        return $country->fresh();
    }
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->all();
    }

    public function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getActive();
    }
    public function getCountries(array $params)
    {
        $filters = [
            'status' => $params['status'] ?? null,
            'en' => $params['en'] ?? null,
            'abb' => $params['abb'] ?? null,
            'phone_code' => $params['phone_code'] ?? null,
            'zone_id' => $params['zone_id'] ?? null,
            'currency_id' => $params['currency_id'] ?? null,
        ];
        $perPage = $params['per_page'] ?? 15;
        $paginate = $params['paginate'] ?? true;
        return $this->repo->getCountries($filters, $perPage, $paginate);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }
    public function toggle_status($id)
    {
        $country = $this->repo->find($id);
        $newStatus = !$country->status;
        return $this->repo->update($country, ['status' => $newStatus]);
    }

    public function getTrashed(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repo->getTrashed($perPage);
    }

    public function restore($id)
    {
        $country = $this->repo->findTrashed($id);
        return $this->repo->restore($country);
    }

    public function forceDelete($id)
    {
        $country = $this->repo->findTrashed($id);
        return $this->repo->forceDelete($country);
    }
}
