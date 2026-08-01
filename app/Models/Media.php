<?php

namespace App\Models;

use App\Enums\MediaFileType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'file_path',
        'file_type',
        'title',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'file_type' => MediaFileType::class,
            'sort_order' => 'integer',
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
