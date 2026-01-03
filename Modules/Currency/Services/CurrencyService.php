<?php

namespace Modules\Currency\Services;

use Modules\Currency\App\Models\Currency;
use Modules\Currency\Repositories\CurrencyRepository;

class CurrencyService
{
    protected CurrencyRepository $repo;

    public function __construct(CurrencyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->getActive();
    }
    public function convertFromUsd($amount, Currency $currency)
    {
        if (!$currency || !$currency->rate_to_usd) return $amount;
        return $amount / $currency->rate_to_usd;
    }
}
