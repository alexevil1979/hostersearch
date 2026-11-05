<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvisorQuestion extends Model
{
    protected $fillable = [
        'slug', 'question', 'question_ru', 'type', 'meta', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AdvisorAnswer::class)->orderBy('sort_order');
    }
}
