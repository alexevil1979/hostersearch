<?php

namespace App\Models;

use App\Enums\CompanyStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'name', 'name_ru', 'logo', 'website', 'affiliate_url',
        'founded', 'headquarters', 'short_description', 'short_description_ru',
        'description', 'description_ru', 'expert_review', 'expert_review_ru',
        'pros', 'pros_ru', 'cons', 'cons_ru', 'rating_breakdown',
        'overall_score', 'review_count', 'price_from', 'price_currency',
        'status', 'claimed_by_user_id', 'claimed_at', 'is_featured',
        'is_recommended', 'view_count', 'click_count', 'meta_title',
        'meta_description', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'pros' => 'array',
            'pros_ru' => 'array',
            'cons' => 'array',
            'cons_ru' => 'array',
            'rating_breakdown' => 'array',
            'overall_score' => 'float',
            'price_from' => 'decimal:2',
            'status' => CompanyStatus::class,
            'claimed_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'founded' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function claimedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(CompanyLocation::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'company_feature')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(CompanyType::class, 'company_company_type')
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'company_category')
            ->withPivot(['is_primary', 'rank_in_category'])
            ->withTimestamps();
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    public function comparisons(): BelongsToMany
    {
        return $this->belongsToMany(Comparison::class, 'comparison_company')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereIn('status', [
            CompanyStatus::Approved->value,
            CompanyStatus::Claimed->value,
        ]);
    }

    public function scopeRecommended(Builder $query): Builder
    {
        return $query->approved()->where('is_recommended', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->approved()->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->whereFullText(['name', 'name_ru', 'short_description', 'description'], $term);
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

    public function visitUrl(): string
    {
        return route('go', $this->slug);
    }
}
