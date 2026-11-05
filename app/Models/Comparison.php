<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comparison extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'title_ru', 'description', 'description_ru',
        'meta_title', 'meta_description', 'is_popular', 'is_published',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'is_popular' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'comparison_company')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }
}
