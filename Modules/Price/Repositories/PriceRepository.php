<?php

namespace Modules\Price\Repositories;

use Modules\Price\App\Models\Price;

class PriceRepository
{
    public function getPrices($id,$perPage=15)
    {
        return Price::where('product_id',$id)->with(['zone'])->paginate($perPage);
    }

    public function createOrUpdate($data)
    {
        return Price::updateOrCreate(
            [
                'zone_id'  => $data['zone_id'],
                'product_id' => $data['product_id'],
            ],
            [
                'price' => $data['price'],
            ]
        );
    }
    public function find(int $id)
    {
        return Price::findOrFail($id);
    }

    public function delete(Price $price): bool
    {
        return $price->delete();
    }

    public function update($rows)
    {
         Price::upsert(
            $rows,
            ['product_id', 'zone_id'],
            ['price', 'updated_at']
        );
        return response()->json([
            'success' => true
        ]);
    }
}
