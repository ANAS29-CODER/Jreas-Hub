<?php

namespace App\Filament\Resources;

use App\Enums\ActivityLogType;
use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'سجل الأنشطة';

    protected static ?string $modelLabel = 'نشاط';

    protected static ?string $pluralModelLabel = 'سجل الأنشطة';

    public static function getEloquentQuery(): Builder
    {
        return app(ActivityLogService::class)->query();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required(),

                Textarea::make('description')
                    ->label('الوصف')
                    ->nullable(),

                Select::make('type')
                    ->label('النوع')
                    ->options([
                        ActivityLogType::Course->value => 'دورة',
                        ActivityLogType::Workshop->value => 'ورشة عمل',
                        ActivityLogType::Initiative->value => 'مبادرة',
                        ActivityLogType::Training->value => 'تدريب',
                        ActivityLogType::Event->value => 'فعالية',
                    ])
                    ->required(),

                DatePicker::make('date')
                    ->label('التاريخ'),

                FileUpload::make('thumbnail')
                    ->label('الصورة المصغرة')
                    ->image()
                    ->disk('public')
                    ->directory('activities')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('الصورة')
                    ->disk('public'),

                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

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
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        ActivityLogType::Course->value => 'دورة',
                        ActivityLogType::Workshop->value => 'ورشة عمل',
                        ActivityLogType::Initiative->value => 'مبادرة',
                        ActivityLogType::Training->value => 'تدريب',
                        ActivityLogType::Event->value => 'فعالية',
                    ]),

                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('من تاريخ'),
                        DatePicker::make('date_to')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['date_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['date_to'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '<=', $date));
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (ActivityLog $record) => app(ActivityLogService::class)->delete($record->getKey())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLog::route('/create'),
            'edit' => Pages\EditActivityLog::route('/{record}/edit'),
        ];
    }
}
