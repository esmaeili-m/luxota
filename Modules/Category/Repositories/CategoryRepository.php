<?php
namespace Modules\Category\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Category\App\Models\Category;
use Modules\Product\App\Models\Product;

class CategoryRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderBy('order')->where('status',1)->get();
    }
    public function getTrashedCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::onlyTrashed()->orderBy('order')->get();
    }

    public function getCategories(array $filters = [], $perPage = 15, $paginate = true, $sort = ['by' => 'id', 'direction' => 'asc'])
    {
        $query = Category::query();

        if (!empty($filters)) {
            $query->search($filters);
        }

        if (!empty($sort['by']) && !empty($sort['direction'])) {
            $query->orderBy($sort['by'], $sort['direction']);
        }

        return $paginate
            ? $query->paginate($perPage)
            : $query->get();
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


    public function getChildrenCategory(int $id,int $perPage= 15):LengthAwarePaginator
    {
        return Category::where('parent_id',$id)->paginate($perPage);
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
        return Category::where('slug',$slug)->where('status',1)->with(['children' => function($childern){
            $childern->where('status',1);
        }])->first();
    }

    public function getAllProducts(Category $category)
    {
        $ids = $this->getAllCategoryIds($category);
        return Product::whereIn('category_id', $ids)->where('status',1)->with(['category' => function($query){
            $query->select('id', 'title');
        }])->get();
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
