<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'title_ru', 'body', 'body_ru',
        'meta_title', 'meta_description', 'is_published', 'template',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
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
}
