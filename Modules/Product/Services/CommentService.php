<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\CommentRepository;

class CommentService
{
    public CommentRepository $repo;
    public function __construct(CommentRepository $repo)
    {
        $this->repo= $repo;
    }

    public function createComment($data)
    {
        $data['user_id']= auth()->user()->id;
        return $this->repo->create($data);
    }

}
