<?php
namespace Modules\Category\Repositories;
use Modules\Category\App\Models\Category;

class CategoryRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::all();
    }

    public function find($id)
    {
        return Category::find($id);
    }
}
