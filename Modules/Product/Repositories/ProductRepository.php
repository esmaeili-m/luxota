<?php

namespace Modules\Product\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class ProductRepository
{
    public function getProducts(array $filters = [], $perPage = 15, $page = 1, $paginate = true)
    {
        $query = Product::query();
        if (isset($filters['with'])) {
            $query->with([$filters['with']]);
        }
        if (!empty($filters)) {
            $query->search($filters);
        }

        return $paginate
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }

    public function getTrashedProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::onlyTrashed()->with('category')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with('category')->orderBy('order')->paginate($perPage);
    }

    public function find(int $id, array $with = [])
    {
        return Product::with($with)->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return Product::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function restore(Product $product)
    {
        return $product->restore();
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
    }
    public function updatePrices(array $productsData)
    {
        foreach ($productsData as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $prices = $product->prices ?? [];

            foreach ($item['prices'] as $p) {
                $prices[$p['zone_id']] = $p['price'];
            }

            $product->prices = $prices;
            $product->save();
        }
    }

    public function getNextProductCode()
    {
        return Product::max('product_code') + 1;
    }

    public function getNextOrder()
    {
        return Product::max('order') + 1;
    }

    public function slugExists(string $slug): bool
    {
        return Product::where('slug', $slug)->exists();
    }

    public function getBySlug($slug)
    {
        return Product::where('slug',$slug)->where('status',1)->with(['category','prices','author.products', 'comments.user', 'comments.comments.user','active_item'])->first();
    }
}
