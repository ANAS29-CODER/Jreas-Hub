<?php

namespace App\Filament\Resources;

use App\Enums\PublishStatus;
use App\Filament\Resources\DonationCampaignResource\Pages;
use App\Models\DonationCampaign;
use App\Services\DonationCampaignService;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DonationCampaignResource extends Resource
{
    protected static ?string $model = DonationCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'حملات التبرع';

    protected static ?string $modelLabel = 'حملة تبرع';

    protected static ?string $pluralModelLabel = 'حملات التبرع';

    public static function getEloquentQuery(): Builder
    {
        return app(DonationCampaignService::class)->query();
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

                TextInput::make('goal_amount')
                    ->label('المبلغ المستهدف')
                    ->numeric()
                    ->nullable(),

                TextInput::make('current_amount')
                    ->label('المبلغ الحالي')
                    ->numeric()
                    ->default(0)
                    ->required(),

                DatePicker::make('start_date')
                    ->label('تاريخ البداية'),

                DatePicker::make('end_date')
                    ->label('تاريخ النهاية')
                    ->nullable(),

                FileUpload::make('thumbnail')
                    ->label('الصورة المصغرة')
                    ->image()
                    ->disk('public')
                    ->directory('donations'),

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

                TextColumn::make('goal_amount')
                    ->label('المبلغ المستهدف')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('current_amount')
                    ->label('المبلغ الحالي')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('progress')
                    ->label('نسبة الإنجاز')
                    ->state(function (DonationCampaign $record) {
                        if (! $record->goal_amount || (float) $record->goal_amount <= 0) {
                            return '0%';
                        }

                        $percentage = ((float) $record->current_amount / (float) $record->goal_amount) * 100;

                        return number_format($percentage, 1).'%';
                    }),

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

                TextColumn::make('end_date')
                    ->label('تاريخ النهاية')
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
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label('تبديل الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (DonationCampaign $record) {
                        $next = match ($record->status) {
                            PublishStatus::Draft => PublishStatus::Published,
                            PublishStatus::Published => PublishStatus::Archived,
                            PublishStatus::Archived => PublishStatus::Draft,
                        };

                        app(DonationCampaignService::class)->update($record->getKey(), ['status' => $next->value]);
                    }),

                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (DonationCampaign $record) => app(DonationCampaignService::class)->delete($record->getKey())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonationCampaigns::route('/'),
            'create' => Pages\CreateDonationCampaign::route('/create'),
            'edit' => Pages\EditDonationCampaign::route('/{record}/edit'),
        ];
    }
}
