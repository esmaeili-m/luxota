<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Tag;

class TagRepository
{
    public function getTree()
    {
        return Tag::whereNull('parent_id')
            ->with('children')
            ->get();
    }

    public function getFlat()
    {
        return Tag::get();
    }

    public function create($data)
    {
        return Tag::create($data);
    }

    public function update(Tag $tag,$data)
    {
        return $tag->update($data);
    }
    public function find(int $id)
    {
        return Tag::findOrFail($id);
    }
}
