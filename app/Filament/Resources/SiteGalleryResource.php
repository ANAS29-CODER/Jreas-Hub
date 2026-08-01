<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteGalleryResource\Pages;
use App\Models\SiteGallery;
use App\Services\SiteGalleryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SiteGalleryResource extends Resource
{
    protected static ?string $model = SiteGallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'معرض الصور';

    protected static ?string $modelLabel = 'صورة';

    protected static ?string $pluralModelLabel = 'معرض الصور';

    public static function getEloquentQuery(): Builder
    {
        return app(SiteGalleryService::class)->query();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image_path')
                    ->label('الصور')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->multiple()
                    ->required()
                    ->afterStateHydrated(fn ($component, $state) => $component->state(is_array($state) ? $state : array_filter([$state]))),

                TextInput::make('caption')
                    ->label('الوصف')
                    ->nullable(),

                TextInput::make('sort_order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('الصورة')
                    ->disk('public'),

                TextColumn::make('caption')
                    ->label('الوصف'),

                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (SiteGallery $record) => app(SiteGalleryService::class)->delete($record->getKey())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteGalleries::route('/'),
            'create' => Pages\CreateSiteGallery::route('/create'),
            'edit' => Pages\EditSiteGallery::route('/{record}/edit'),
        ];
    }
}
