<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteGallery extends Model
{
    protected $table = 'site_gallery';

    protected $fillable = [
        'image_path',
        'caption',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
