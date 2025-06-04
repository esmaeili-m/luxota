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
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Category::paginate($perPage);
    }
    public function find($id)
    {
        return Category::find($id);
    }
    public function create(array $data)
    {
        return Category::create($data);
    }
}
