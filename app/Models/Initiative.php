<?php

namespace App\Models;

use App\Enums\InitiativeType;
use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Initiative extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'category_id',
        'manager_name',
        'start_date',
        'end_date',
        'thumbnail',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => InitiativeType::class,
            'status' => PublishStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Initiative $initiative) {
            if (empty($initiative->slug)) {
                $initiative->slug = Str::slug($initiative->title);
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
