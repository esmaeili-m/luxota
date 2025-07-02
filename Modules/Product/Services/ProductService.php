<?php

namespace Modules\Product\Services;

use App\Services\Uploader;
use Modules\Product\Repositories\ProductRepository;

class ProductService
{
    protected ProductRepository $repo;

    public function __construct(ProductRepository $repo)
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

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = Uploader::uploadImage($data['image'], 'Products');
        }
        return $this->repo->create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->repo->find($id);

        if (!$product) {
            return null;
        }

        if (isset($data['image'])) {
            if ($product->image) {
                Uploader::deleteImage($product->image);
            }
            $data['image'] = Uploader::uploadImage($data['image'], 'categories');
        }
        $this->repo->update($product, $data);

        return $product->fresh();
    }


    public function delete(int $id): bool
    {
        $product = $this->repo->find($id);
        if (!$product) {
            return false;
        }
        return $product->delete();
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }
}
