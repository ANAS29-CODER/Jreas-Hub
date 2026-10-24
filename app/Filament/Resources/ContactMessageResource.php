<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasCountBadge;
use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    use HasCountBadge;

    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'رسائل التواصل';

    protected static ?string $modelLabel = 'رسالة تواصل';

    protected static ?string $pluralModelLabel = 'رسائل التواصل';

    protected static ?string $navigationGroup = 'الموقع';

    /**
     * Unread messages are what the admin needs to act on, so they take priority over the total.
     */
    public static function getNavigationBadge(): ?string
    {
        $unread = ContactMessage::where('is_read', false)->count();

        return (string) ($unread > 0 ? $unread : ContactMessage::count());
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return ContactMessage::where('is_read', false)->exists() ? 'warning' : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return ContactMessage::where('is_read', false)->exists() ? 'رسائل غير مقروءة' : 'العدد الكلي';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),

                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),

                TextInput::make('phone')
                    ->label('رقم الهاتف'),

                Textarea::make('message')
                    ->label('الرسالة')
                    ->required()
                    ->columnSpanFull(),

                Toggle::make('is_read')
                    ->label('تمت القراءة'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('مقروءة')
                    ->boolean(),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('الهاتف'),

                TextColumn::make('message')
                    ->label('الرسالة')
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContactMessages::route('/'),
        ];
    }
}
