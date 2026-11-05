<?php

use App\Support\Money;

if (! function_exists('money')) {
    function money(float|string|null $amountUsd, ?string $currency = null): string
    {
        return Money::format($amountUsd, $currency);
    }
}

if (! function_exists('t_field')) {
    /**
     * Pick localized model field (name / name_ru).
     */
    function t_field(object $model, string $field, ?string $locale = null): mixed
    {
        if (method_exists($model, 'localized')) {
            return $model->localized($field, $locale);
        }

        $locale ??= app()->getLocale();
        $ru = $field.'_ru';

        if ($locale === 'ru' && isset($model->{$ru}) && filled($model->{$ru})) {
            return $model->{$ru};
        }

        return $model->{$field} ?? null;
    }
}
