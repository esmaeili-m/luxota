<?php

namespace Modules\Language\Repositories;

use Modules\Language\App\Models\Language;

class LanguageRepository
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Language::all();
    }
}
