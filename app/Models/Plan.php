<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'slug', 'name', 'name_ru', 'description',
        'price', 'price_renewal', 'currency', 'billing_cycle',
        'space_gb', 'cpu', 'ram', 'sites', 'bandwidth', 'os', 'panel',
        'score', 'is_featured', 'is_active', 'affiliate_url', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_renewal' => 'decimal:2',
            'score' => 'float',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'plan_feature')
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
