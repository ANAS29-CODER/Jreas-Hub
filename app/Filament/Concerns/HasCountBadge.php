<?php

namespace App\Filament\Concerns;

/**
 * Shows the number of records next to the resource in the admin sidebar.
 */
trait HasCountBadge
{
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'العدد الكلي';
    }
}
