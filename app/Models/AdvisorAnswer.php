<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisorAnswer extends Model
{
    protected $fillable = [
        'advisor_question_id', 'slug', 'label', 'label_ru', 'weights', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'weights' => 'array',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(AdvisorQuestion::class, 'advisor_question_id');
    }
}
