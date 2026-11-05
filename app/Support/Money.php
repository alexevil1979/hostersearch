<?php

namespace App\Support;

class Money
{
    public static function convert(float|string|null $amountUsd, ?string $currency = null): float
    {
        $amount = (float) ($amountUsd ?? 0);
        $currency = strtoupper($currency ?? (session('currency') ?? config('hostersearch.default_currency', 'USD')));

        if ($currency === 'EUR') {
            return round($amount * (float) config('hostersearch.usd_to_eur_rate', 0.92), 2);
        }

        return round($amount, 2);
    }

    public static function format(float|string|null $amountUsd, ?string $currency = null): string
    {
        $currency = strtoupper($currency ?? (session('currency') ?? 'USD'));
        $amount = self::convert($amountUsd, $currency);
        $symbol = $currency === 'EUR' ? '€' : '$';

        return $symbol.number_format($amount, 2);
    }
}
