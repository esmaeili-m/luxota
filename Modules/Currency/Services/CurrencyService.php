<?php

namespace Modules\Currency\Services;

use Modules\Currency\App\Models\Currency;

class CurrencyService
{
    public function convertFromUsd($amount, Currency $currency)
    {
        if (!$currency || !$currency->rate_to_usd) return $amount;
        return $amount / $currency->rate_to_usd;
    }
}
