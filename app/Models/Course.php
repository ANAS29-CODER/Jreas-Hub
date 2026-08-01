<?php

namespace App\Models;

use App\Enums\CourseLevel;
use App\Enums\DeliveryType;
use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'category_id',
        'instructor_name',
        'type',
        'level',
        'duration',
        'seats',
        'start_date',
        'end_date',
        'location',
        'meeting_link',
        'thumbnail',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => DeliveryType::class,
            'level' => CourseLevel::class,
            'status' => PublishStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
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
}
