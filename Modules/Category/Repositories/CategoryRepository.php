<?php
namespace Modules\Category\Repositories;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Category\App\Models\Category;

class CategoryRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::all();
    }
    public function getTrashedCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::onlyTrashed()->whereNull('parent_id')->get();
    }
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Category::whereNull('parent_id')->paginate($perPage);
    }

    public function find(int $id, array $with = [])
    {
        return Category::with($with)->findOrFail($id);
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

    public function searchByFields(array $filters)
    {
        $query = Category::query();

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
