<?php

namespace App\Filament\Resources;

use App\Enums\InitiativeType;
use App\Enums\PublishStatus;
use App\Filament\Concerns\HasCountBadge;
use App\Filament\Resources\InitiativeResource\Pages;
use App\Models\Initiative;
use App\Services\InitiativeService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class InitiativeResource extends Resource
{
    use HasCountBadge;

    protected static ?string $model = Initiative::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'المبادرات';

    protected static ?string $modelLabel = 'مبادرة';

    protected static ?string $pluralModelLabel = 'المبادرات';

    protected static ?string $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function getEloquentQuery(): Builder
    {
        return app(InitiativeService::class)->query();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $get, $state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('الرابط المختصر')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                RichEditor::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),

                Select::make('type')
                    ->label('النوع')
                    ->options([
                        InitiativeType::Student->value => 'طلابية',
                        InitiativeType::Community->value => 'مجتمعية',
                    ])
                    ->required(),

                TextInput::make('manager_name')
                    ->label('اسم المسؤول'),

                DatePicker::make('start_date')
                    ->label('تاريخ البداية'),

                DatePicker::make('end_date')
                    ->label('تاريخ النهاية')
                    ->nullable(),

                FileUpload::make('thumbnail')
                    ->label('الصورة المصغرة')
                    ->image()
                    ->disk('public')
                    ->directory('initiatives'),

                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        PublishStatus::Draft->value => 'مسودة',
                        PublishStatus::Published->value => 'منشور',
                        PublishStatus::Archived->value => 'مؤرشف',
                    ])
                    ->required(),
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
                    ->formatStateUsing(fn (InitiativeType $state) => match ($state) {
                        InitiativeType::Student => 'طلابية',
                        InitiativeType::Community => 'مجتمعية',
                    }),

                TextColumn::make('manager_name')
                    ->label('اسم المسؤول'),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (PublishStatus $state) => match ($state) {
                        PublishStatus::Draft => 'مسودة',
                        PublishStatus::Published => 'منشور',
                        PublishStatus::Archived => 'مؤرشف',
                    })
                    ->color(fn (PublishStatus $state) => match ($state) {
                        PublishStatus::Draft => 'gray',
                        PublishStatus::Published => 'success',
                        PublishStatus::Archived => 'danger',
                    }),

                TextColumn::make('start_date')
                    ->label('تاريخ البداية')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        PublishStatus::Draft->value => 'مسودة',
                        PublishStatus::Published->value => 'منشور',
                        PublishStatus::Archived->value => 'مؤرشف',
                    ]),

                SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        InitiativeType::Student->value => 'طلابية',
                        InitiativeType::Community->value => 'مجتمعية',
                    ]),

                TrashedFilter::make()
                    ->label('المحذوفة'),
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label('تبديل الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Initiative $record) {
                        $next = match ($record->status) {
                            PublishStatus::Draft => PublishStatus::Published,
                            PublishStatus::Published => PublishStatus::Archived,
                            PublishStatus::Archived => PublishStatus::Draft,
                        };

                        app(InitiativeService::class)->update($record->getKey(), ['status' => $next->value]);
                    }),

                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (Initiative $record) => app(InitiativeService::class)->delete($record->getKey())),

                RestoreAction::make()
                    ->label('استعادة'),

                ForceDeleteAction::make()
                    ->label('حذف نهائي')
                    ->requiresConfirmation(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInitiatives::route('/'),
            'create' => Pages\CreateInitiative::route('/create'),
            'edit' => Pages\EditInitiative::route('/{record}/edit'),
        ];
    }
}
