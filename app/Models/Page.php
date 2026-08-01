<?php

namespace App\Models;

use App\Enums\PageSlug;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'slug' => PageSlug::class,
        ];
    }
}
