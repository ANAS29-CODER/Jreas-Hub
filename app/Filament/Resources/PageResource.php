<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasCountBadge;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use App\Services\PageService;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageResource extends Resource
{
    use HasCountBadge;

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'الصفحات';

    protected static ?string $modelLabel = 'صفحة';

    protected static ?string $pluralModelLabel = 'الصفحات';

    protected static ?string $navigationGroup = 'الموقع';

    protected static ?int $navigationSort = 3;

    protected static bool $canCreate = false;

    public static function getEloquentQuery(): Builder
    {
        return app(PageService::class)->query();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required(),

                RichEditor::make('content')
                    ->label('المحتوى')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('meta_title')
                    ->label('عنوان الميتا')
                    ->nullable(),

                Textarea::make('meta_description')
                    ->label('وصف الميتا')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('الرابط المختصر')
                    ->badge(),

                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->label('تعديل'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
