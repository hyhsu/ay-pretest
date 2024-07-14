<?php

namespace App\Services;

use Illuminate\Support\Str;

class ExchangeRateService
{
    /**
     * 取得匯率
     *
     * @param string $currency
     *
     * @return float
     */
    public function getRate(string $currency): float
    {
        if (Str::upper($currency) === 'USD') {
            return config('order.usd_rate');
        }

        return 1;
    }
}
