<?php

namespace App\Models;

use App\Enums\ActivityLogType;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activities_log';

    protected $fillable = [
        'title',
        'description',
        'type',
        'date',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'type' => ActivityLogType::class,
            'date' => 'date',
        ];
    }
}
