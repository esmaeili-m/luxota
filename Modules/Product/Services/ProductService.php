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

    public function getProducts(array $filters)
    {
        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;
        $paginate = $filters['paginate'] ?? true;
        return $this->repo->getProducts($filters, $perPage, $page, $paginate);
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
        if (isset($data['image']) && is_file($data['image'])) {
            $data['image'] = Uploader::uploadImage($data['image'], 'products');
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']['en']);
            if ($this->repo->slugExists($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['slug']);
            }
        }

        if (empty($data['product_code'])) {
            $data['product_code'] = $this->repo->getNextProductCode();
        }
        if (empty($data['last_version_update_date'])) {
            $data['last_version_update_date'] = now();
        }

        if (empty($data['order'])) {
            $data['order'] = $this->repo->getNextOrder();
        }

        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Product
    {
        $product = $this->repo->find($id);

        if (!$product) {
            return null;
        }

        if (isset($data['image']) && is_file($data['image'])) {
            if ($product->image) {
                Uploader::deleteImage($product->image);
            }
            $data['image'] = Uploader::uploadImage($data['image'], 'products');
        }

        if (!isset($data['order'])) {
            $data['order'] = $product->order;
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
    protected function generateUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $counter = 1;

        while ($this->repo->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getProductBySlug($slug)
    {
        return $this->repo->getBySlug($slug);
    }
    public function updateProductPrices(array $productsData)
    {
        // مثال: اگر قیمت منفی بود حذف شود
        foreach ($productsData as &$item) {
            foreach ($item['prices'] as &$price) {
                if ($price['price'] < 0) {
                    $price['price'] = 0;
                }
            }
        }

        $this->repo->updatePrices($productsData);
    }
}
