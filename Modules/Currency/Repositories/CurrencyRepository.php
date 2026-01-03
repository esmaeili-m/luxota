<?php

namespace Modules\Currency\Repositories;

use Modules\Country\App\Models\Country;
use Modules\Currency\App\Models\Currency;

class CurrencyRepository
{
    public function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Currency::active()->get();
    }
}
