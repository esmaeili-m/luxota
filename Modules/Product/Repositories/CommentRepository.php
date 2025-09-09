<?php

namespace Modules\Product\Repositories;

use Modules\Product\App\Models\Comment;

class CommentRepository
{

    public function create($data)
    {
        return Comment::create($data);
    }
}
