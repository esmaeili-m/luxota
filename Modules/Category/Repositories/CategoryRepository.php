<?php
namespace Modules\Category\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Category\App\Models\Category;
use Modules\Product\App\Models\Product;

class CategoryRepository
{


    public function getCategories(array $filters = [], $perPage = 15, $page = 1, $paginate = true)
    {
        $query = Category::query();
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

    public function getTrashedCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::onlyTrashed()->orderBy('order')->get();
    }

    public function find(int $id, array $with = [])
    {
        return Category::with(['children','parent'])->findOrFail($id);
    }

    public function findTrashedById(int $id)
    {
        return Category::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function restore(Category $category)
    {
        return $category->restore();

    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
    }

    public function getNextCategoryCode()
    {
        return Category::max('category_code') + 1;
    }

    public function getNextOrder()
    {
        return Category::max('order') + 1;
    }

    public function slugExists(string $slug): bool
    {
        return Category::where('slug', $slug)->exists();
    }

    public function getBySlug($slug)
    {
        return Category::where('slug',$slug)->where('status',1)->with(['children.products' => function($childern){
            $childern->where('status',1);
        }])->first();
    }

    private function getAllCategoryIds(Category $category)
    {
        $ids = [$category->id];

        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }

        return $ids;
    }
}
