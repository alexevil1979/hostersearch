<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewReply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'review_id', 'user_id', 'company_id', 'body', 'is_official',
    ];

    protected function casts(): array
    {
        return [
            'is_official' => 'boolean',
        ];
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
