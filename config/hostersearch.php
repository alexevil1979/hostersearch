<?php

return [
    'name' => env('APP_NAME', 'HosterSearch'),
    'default_currency' => env('DEFAULT_CURRENCY', 'USD'),
    'supported_locales' => array_filter(explode(',', env('SUPPORTED_LOCALES', 'en,ru'))),
    'supported_currencies' => array_filter(explode(',', env('SUPPORTED_CURRENCIES', 'USD,EUR'))),
    'usd_to_eur_rate' => (float) env('USD_TO_EUR_RATE', 0.92),
];
