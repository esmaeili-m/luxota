<?php

namespace Modules\Planner\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Planner\Repositories\CommentRepository;

class CommentService
{
    public CommentRepository $repo;

    public function __construct(CommentRepository $repo)
    {
        $this->repo=$repo;
    }
    public function create($data)
    {
        $data['user_id']=Auth::id();
        return $this->repo->create($data);

    }
}
