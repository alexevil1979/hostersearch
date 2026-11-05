<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisorResult extends Model
{
    protected $fillable = [
        'session_id', 'user_id', 'answers', 'recommended_company_ids',
        'scores', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'recommended_company_ids' => 'array',
            'scores' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
