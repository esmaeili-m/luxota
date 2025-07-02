<?php

namespace Modules\Product\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class ProductRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::all();
    }
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }
    public function find(int $id, array $with = [])
    {
        return Product::with($with)->findOrFail($id);
    }
    public function create(array $data)
    {
        return Product::create($data);
    }
    public function update(Product $category, array $data): bool
    {
        return $category->update($data);
    }
    public function delete(Product $category): bool
    {
        return $category->delete();
    }


    public function searchByFields(array $filters)
    {
        $query = Product::query();

        foreach ($filters as $field => $value) {
            if (empty($value)) continue;

            switch ($field) {
                case 'title':
                    $query->where('title', 'like', "%{$value}%");
                    break;

                case 'subtitle':
                    $query->where('subtitle', 'like', "%{$value}%");
                    break;

                case 'slug':
                    $query->where('slug', 'like', "%{$value}%");
                    break;

                case 'status':
                    $query->where('status', $value);
                    break;

            }
        }

        return $query->get();
    }
}
