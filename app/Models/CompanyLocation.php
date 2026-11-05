<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyLocation extends Model
{
    protected $fillable = [
        'company_id', 'country_code', 'country_name', 'city',
        'is_datacenter', 'is_headquarters',
    ];

    protected function casts(): array
    {
        return [
            'is_datacenter' => 'boolean',
            'is_headquarters' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
