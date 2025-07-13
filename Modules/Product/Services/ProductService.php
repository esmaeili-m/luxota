<?php

namespace Modules\Product\Services;

use App\Services\Uploader;
use Illuminate\Support\Str;
use Modules\Product\App\Models\Product;
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

    public function getTrashedProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedProducts();
    }

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }

    public function create(array $data)
    {
        // Handle image upload if it's a file
        if (isset($data['image']) && is_file($data['image'])) {
            $data['image'] = Uploader::uploadImage($data['image'], 'products');
        }
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']['en']);
        }
        
        // Set default values for required fields
        if (empty($data['last_version_update_date'])) {
            $data['last_version_update_date'] = now();
        }
        
        if (empty($data['product_code'])) {
            $data['product_code'] = Product::max('product_code') + 1;
        }
        
        if (empty($data['order'])) {
            $data['order'] = Product::max('order') + 1;
        }
        
        if (!isset($data['status'])) {
            $data['status'] = true;
        }
        
        if (!isset($data['show_price'])) {
            $data['show_price'] = true;
        }
        
        if (!isset($data['payment_type'])) {
            $data['payment_type'] = true;
        }
        
        if (!isset($data['version'])) {
            $data['version'] = 1.0;
        }
        
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Product
    {
        $product = $this->repo->find($id);

        if (!$product) {
            return null;
        }

        // Handle image upload if it's a file
        if (isset($data['image']) && is_file($data['image'])) {
            if ($product->image) {
                Uploader::deleteImage($product->image);
            }
            $data['image'] = Uploader::uploadImage($data['image'], 'products');
        }

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']['en']);
        }

        // Ensure order is provided for updates
        if (!isset($data['order'])) {
            $data['order'] = $product->order;
        }
        
        // Ensure product_code is provided for updates
        if (!isset($data['product_code'])) {
            $data['product_code'] = $product->product_code;
        }
        
        // Ensure last_version_update_date is provided for updates
        if (!isset($data['last_version_update_date'])) {
            $data['last_version_update_date'] = $product->last_version_update_date;
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

    public function destroy($id)
    {
        $this->service->deleteProduct($id);

        return response()->json(['message' => 'Product soft deleted successfully']);
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreProduct($id)
    {
        $product = $this->repo->findTrashedById($id);
        if (!$product) {
            return false;
        }
        $this->repo->restore($product);
    }

    public function forceDeleteProduct($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $product = $this->repo->find($id);
        $newStatus = !$product->status;
        return $this->repo->update($product, ['status' => $newStatus]);
    }
}
