<?php

namespace Modules\Category\Services;

use App\Services\Uploader;
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

    public function getById(int $id, array $with = [])
    {
        return $this->repo->find($id, $with);
    }
    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = Uploader::uploadImage($data['image'], 'categories');
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

    public function searchByFields(array $filters): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->repo->searchByFields($filters);
    }

}
