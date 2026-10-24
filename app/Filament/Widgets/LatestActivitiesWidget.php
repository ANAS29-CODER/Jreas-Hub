<?php

namespace App\Filament\Widgets;

use App\Enums\ActivityLogType;
use App\Services\ActivityLogService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestActivitiesWidget extends BaseWidget
{
    protected static bool $isLazy = true;

    protected static ?string $heading = 'آخر الأنشطة';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                app(ActivityLogService::class)->query()
                    ->latest('date')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان'),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn (ActivityLogType $state) => match ($state) {
                        ActivityLogType::Course => 'دورة',
                        ActivityLogType::Workshop => 'ورشة عمل',
                        ActivityLogType::Initiative => 'مبادرة',
                        ActivityLogType::Training => 'تدريب',
                        ActivityLogType::Event => 'فعالية',
                    }),

                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date(),
            ])
            ->paginated(false);
    }
}
