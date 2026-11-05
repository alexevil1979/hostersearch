<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use App\Enums\ReviewType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'user_id', 'type', 'author_name', 'author_email',
        'rating_overall', 'ratings_json', 'title', 'body', 'pros', 'cons',
        'status', 'is_featured', 'is_verified', 'helpful_count', 'locale',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReviewType::class,
            'status' => ReviewStatus::class,
            'ratings_json' => 'array',
            'pros' => 'array',
            'cons' => 'array',
            'rating_overall' => 'float',
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ReviewReply::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', ReviewStatus::Approved);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->whereFullText(['title', 'body'], $term);
    }
}
