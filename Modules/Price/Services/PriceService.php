<?php

namespace Modules\Price\Services;

use Modules\Price\Repositories\PriceRepository;

class PriceService
{
    public PriceRepository $repo;

    public function __construct(PriceRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getProductPrices($id, $perPage=15)
    {
        return $this->repo->getPrices($id, $perPage=15);
    }

    public function createOrUpdate($data)
    {
        return $this->repo->createOrUpdate($data);
    }

    public function delete($id)
    {
        $price = $this->repo->find($id);
        if (!$price) {
            return false;
        }
        return $price->delete();
    }

    public function update($data)
    {
        $rows = [];

        foreach ($data as $item) {
            // validation ساده
            if (!isset($item['product_id'], $item['zone_id'], $item['amount'])) {
                continue;
            }

            $rows[] = [
                'product_id' => $item['product_id'],
                'zone_id'    => $item['zone_id'],
                'price'      => $item['amount'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $this->repo->update($rows);
    }
}
