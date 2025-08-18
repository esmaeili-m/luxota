<?php

namespace Modules\Product\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class ProductRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')->get();
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

    public function searchByFields(array $filters)
    {
        $query = Product::with('category');

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'title':
                    $query->where('title', 'like', "%{$value}%");
                    break;

                case 'description':
                    $query->where('description', 'like', "%{$value}%");
                    break;

                case 'product_code':
                    $query->where('product_code', $value);
                    break;

                case 'slug':
                    $query->where('slug', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;

                case 'category_id':
                    $query->where('category_id', $value);
                    break;

                case 'show_price':
                    $query->where('show_price', $value);
                    break;

                case 'payment_type':
                    $query->where('payment_type', $value);
                    break;

                case 'version':
                    $query->where('version', $value);
                    break;

                case 'order':
                    $query->where('order', $value);
                    break;

                case 'video_script':
                    $query->where('video_script', 'like', "%{$value}%");
                    break;

                case 'image':
                    $query->where('image', 'like', "%{$value}%");
                    break;
            }
        }

        return $query->get();
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
}
