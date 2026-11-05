<?php

namespace App\Models;

use App\Enums\ClaimStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Claim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'user_id', 'full_name', 'email', 'job_title',
        'phone', 'company_email', 'message', 'status', 'reviewed_by',
        'reviewed_at', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'status' => ClaimStatus::class,
            'reviewed_at' => 'datetime',
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

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
