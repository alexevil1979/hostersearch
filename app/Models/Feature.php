<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $fillable = [
        'slug', 'name', 'name_ru', 'icon', 'group', 'sort_order',
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_feature')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_feature')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function localized(string $field, ?string $locale = null): mixed
    {
        $locale ??= app()->getLocale();
        $ruField = $field.'_ru';

        if ($locale === 'ru' && ! empty($this->{$ruField})) {
            return $this->{$ruField};
        }

        return $this->{$field};
    }
}
