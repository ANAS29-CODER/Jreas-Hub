<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Enums\DeliveryType;
use App\Enums\PublishStatus;
use App\Filament\Resources\WorkshopResource\Pages;
use App\Models\Category;
use App\Models\Workshop;
use App\Services\WorkshopService;
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

class WorkshopResource extends Resource
{
    protected static ?string $model = Workshop::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'ورش العمل';

    protected static ?string $modelLabel = 'ورشة عمل';

    protected static ?string $pluralModelLabel = 'ورش العمل';

    public static function getEloquentQuery(): Builder
    {
        return app(WorkshopService::class)->query();
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

                TextInput::make('instructor_name')
                    ->label('اسم المدرب'),

                Select::make('category_id')
                    ->label('التصنيف')
                    ->options(
                        Category::query()
                            ->where('type', CategoryType::Workshop->value)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Select::make('type')
                    ->label('نوع الورشة')
                    ->options([
                        DeliveryType::Online->value => 'عن بعد',
                        DeliveryType::Offline->value => 'حضوري',
                    ])
                    ->live()
                    ->required(),

                DatePicker::make('date')
                    ->label('التاريخ'),

                TextInput::make('duration')
                    ->label('المدة (ساعات)')
                    ->numeric()
                    ->required(),

                TextInput::make('seats')
                    ->label('عدد المقاعد')
                    ->numeric()
                    ->nullable(),

                TextInput::make('location')
                    ->label('الموقع')
                    ->nullable()
                    ->visible(fn (Get $get) => $get('type') === DeliveryType::Offline->value),

                TextInput::make('meeting_link')
                    ->label('رابط الاجتماع')
                    ->nullable()
                    ->visible(fn (Get $get) => $get('type') === DeliveryType::Online->value),

                TextInput::make('price')
                    ->label('السعر')
                    ->numeric()
                    ->default(0)
                    ->required(),

                FileUpload::make('thumbnail')
                    ->label('الصورة المصغرة')
                    ->image()
                    ->disk('public')
                    ->directory('workshops'),

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

                TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->badge(),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn (DeliveryType $state) => match ($state) {
                        DeliveryType::Online => 'عن بعد',
                        DeliveryType::Offline => 'حضوري',
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
                        DeliveryType::Online->value => 'عن بعد',
                        DeliveryType::Offline->value => 'حضوري',
                    ]),

                SelectFilter::make('category_id')
                    ->label('التصنيف')
                    ->options(
                        Category::query()
                            ->where('type', CategoryType::Workshop->value)
                            ->pluck('name', 'id')
                    ),
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label('تبديل الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Workshop $record) {
                        $next = match ($record->status) {
                            PublishStatus::Draft => PublishStatus::Published,
                            PublishStatus::Published => PublishStatus::Archived,
                            PublishStatus::Archived => PublishStatus::Draft,
                        };

                        app(WorkshopService::class)->update($record->getKey(), ['status' => $next->value]);
                    }),

                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (Workshop $record) => app(WorkshopService::class)->delete($record->getKey())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkshops::route('/'),
            'create' => Pages\CreateWorkshop::route('/create'),
            'edit' => Pages\EditWorkshop::route('/{record}/edit'),
        ];
    }
}
