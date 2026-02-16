<?php

namespace Modules\Planner\Services;

use Modules\Planner\Repositories\TagRepository;

class TagService
{
    protected $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getTags(string $view = 'tree')
    {
        return match ($view) {
            'flat' => $this->repository->getFlat(),
            default => $this->repository->getTree(),
        };
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $tag = $this->repository->find($id);

        if (!$tag) {
            return null;
        }
        $this->repository->update($tag, $data);

        return $tag->fresh();
    }
}
