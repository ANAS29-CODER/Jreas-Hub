<?php

namespace App\Models;

use App\Enums\SettingGroup;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'group' => SettingGroup::class,
        ];
    }
}
