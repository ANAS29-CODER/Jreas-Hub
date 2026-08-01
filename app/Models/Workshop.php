<?php

namespace App\Models;

use App\Enums\DeliveryType;
use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Workshop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'instructor_name',
        'category_id',
        'type',
        'date',
        'duration',
        'seats',
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
            'status' => PublishStatus::class,
            'date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Workshop $workshop) {
            if (empty($workshop->slug)) {
                $workshop->slug = Str::slug($workshop->title);
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
