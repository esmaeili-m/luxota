<?php

namespace Modules\Language\Services;

use Modules\Language\Repositories\LanguageRepository;

class LanguageService
{
    protected LanguageRepository $repo;

    public function __construct(LanguageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->all();
    }
}
