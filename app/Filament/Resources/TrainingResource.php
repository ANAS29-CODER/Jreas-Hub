<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Filament\Concerns\HasCountBadge;
use App\Filament\Resources\TrainingResource\Pages;
use App\Models\Category;
use App\Models\Training;
use App\Services\TrainingService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class TrainingResource extends Resource
{
    use HasCountBadge;

    protected static ?string $model = Training::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'التدريبات';

    protected static ?string $modelLabel = 'تدريب';

    protected static ?string $pluralModelLabel = 'التدريبات';

    protected static ?string $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function getEloquentQuery(): Builder
    {
        return app(TrainingService::class)->query();
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

                Select::make('category_id')
                    ->label('التصنيف')
                    ->options(
                        Category::query()
                            ->where('type', CategoryType::Training->value)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->nullable(),

                TextInput::make('department')
                    ->label('القسم'),

                TextInput::make('university')
                    ->label('الجامعة'),

                TextInput::make('supervisor_name')
                    ->label('اسم المشرف'),

                TextInput::make('available_seats')
                    ->label('المقاعد المتاحة')
                    ->numeric()
                    ->nullable(),

                DatePicker::make('start_date')
                    ->label('تاريخ البداية'),

                DatePicker::make('end_date')
                    ->label('تاريخ النهاية'),

                Textarea::make('requirements')
                    ->label('المتطلبات')
                    ->nullable()
                    ->columnSpanFull(),

                FileUpload::make('thumbnail')
                    ->label('الصورة المصغرة')
                    ->image()
                    ->disk('public')
                    ->directory('trainings'),

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

                TextColumn::make('university')
                    ->label('الجامعة')
                    ->searchable(),

                TextColumn::make('department')
                    ->label('القسم')
                    ->searchable(),

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

                SelectFilter::make('university')
                    ->label('الجامعة')
                    ->options(fn () => Training::query()
                        ->whereNotNull('university')
                        ->distinct()
                        ->pluck('university', 'university')
                        ->toArray()),

                TrashedFilter::make()
                    ->label('المحذوفة'),
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label('تبديل الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Training $record) {
                        $next = match ($record->status) {
                            PublishStatus::Draft => PublishStatus::Published,
                            PublishStatus::Published => PublishStatus::Archived,
                            PublishStatus::Archived => PublishStatus::Draft,
                        };

                        app(TrainingService::class)->update($record->getKey(), ['status' => $next->value]);
                    }),

                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (Training $record) => app(TrainingService::class)->delete($record->getKey())),

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
            'index' => Pages\ListTrainings::route('/'),
            'create' => Pages\CreateTraining::route('/create'),
            'edit' => Pages\EditTraining::route('/{record}/edit'),
        ];
    }
}
