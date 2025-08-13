<?php

namespace Modules\Category\Services;

use App\Services\Uploader;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\Category\App\Models\Category;
use Modules\Category\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $repo;

    public function __construct(CategoryRepository $repo)
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

    public function getTrashedCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getTrashedCategories();
    }

    public function getById(int $id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = Uploader::uploadImage($data['image'], 'categories');
        }
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']['en']);
            if ($this->repo->slugExists($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['slug']);
            }
        }
        if (empty($data['category_code'])) {
            $data['category_code'] = $this->repo->getNextCategoryCode();
        }

        if (empty($data['order'])) {
            $data['order'] = $this->repo->getNextOrder();
        }
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): ?Category
    {
        $category = $this->repo->find($id);

        if (!$category) {
            return null;
        }

        if (isset($data['image'])) {
            if ($category->image) {
                Uploader::deleteImage($category->image);
            }
            $data['image'] = Uploader::uploadImage($data['image'], 'categories');
        }
        $this->repo->update($category, $data);

        return $category->fresh();
    }

    public function delete(int $id): bool
    {
        $category = $this->repo->find($id);
        if (!$category) {
            return false;
        }
        return $category->delete();
    }

    public function destroy($id)
    {
        $this->service->deleteCategory($id);

        return response()->json(['message' => 'Category soft deleted successfully']);
    }

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

    public function restoreCategory($id)
    {
        $category = $this->repo->findTrashedById($id);
        if (!$category) {
            return false;
        }
        $this->repo->restore($category);
    }

    public function forceDeleteCategory($id)
    {
        $this->repo->forceDelete($id);
    }

    public function toggle_status($id)
    {
        $category = $this->repo->find($id);
        $newStatus = !$category->status;
        return $this->repo->update($category, ['status' => $newStatus]);
    }

    public function getChildrenCategory(int $id ,int $perPage= 15): LengthAwarePaginator
    {
        return $this->repo->getChildrenCategory($id, $perPage= 15);
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

}
