<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyType extends Model
{
    protected $fillable = [
        'slug', 'name', 'name_ru', 'sort_order',
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_company_type')
            ->withTimestamps();
    }
}
