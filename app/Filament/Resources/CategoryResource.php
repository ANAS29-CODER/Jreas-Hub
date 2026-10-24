<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Filament\Concerns\HasCountBadge;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Services\CategoryService;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    use HasCountBadge;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'التصنيفات';

    protected static ?string $modelLabel = 'تصنيف';

    protected static ?string $pluralModelLabel = 'التصنيفات';

    protected static ?string $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return app(CategoryService::class)->query();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $get, $state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('الرابط المختصر')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                Select::make('type')
                    ->label('النوع')
                    ->options([
                        CategoryType::Course->value => 'دورة',
                        CategoryType::Workshop->value => 'ورشة عمل',
                        CategoryType::Initiative->value => 'مبادرة',
                        CategoryType::Training->value => 'تدريب',
                    ])
                    ->required(),

                TextInput::make('icon')
                    ->label('الأيقونة')
                    ->nullable(),

                ColorPicker::make('color')
                    ->label('اللون')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn (CategoryType $state) => match ($state) {
                        CategoryType::Course => 'دورة',
                        CategoryType::Workshop => 'ورشة عمل',
                        CategoryType::Initiative => 'مبادرة',
                        CategoryType::Training => 'تدريب',
                    }),

                TextColumn::make('icon')
                    ->label('الأيقونة'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        CategoryType::Course->value => 'دورة',
                        CategoryType::Workshop->value => 'ورشة عمل',
                        CategoryType::Initiative->value => 'مبادرة',
                        CategoryType::Training->value => 'تدريب',
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn ($record) => app(CategoryService::class)->delete($record->getKey())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
