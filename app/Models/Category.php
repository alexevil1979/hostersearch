<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id', 'slug', 'name', 'name_ru', 'type', 'description',
        'description_ru', 'icon', 'meta_title', 'meta_description',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => CategoryType::class,
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_category')
            ->withPivot(['is_primary', 'rank_in_category'])
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
