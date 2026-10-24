<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Training extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'department',
        'university',
        'supervisor_name',
        'available_seats',
        'start_date',
        'end_date',
        'requirements',
        'thumbnail',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => PublishStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Training $training) {
            if (empty($training->slug)) {
                $training->slug = Str::slug($training->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
