<?php

namespace Modules\Planner\Repositories;

use Modules\Planner\App\Models\Comment;

class CommentRepository
{
    public function create($data)
    {
        return Comment::create($data);
    }
}
