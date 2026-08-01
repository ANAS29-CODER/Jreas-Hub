<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Enums\CourseLevel;
use App\Enums\DeliveryType;
use App\Enums\PublishStatus;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Category;
use App\Models\Course;
use App\Services\CourseService;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'الدورات';

    protected static ?string $modelLabel = 'دورة';

    protected static ?string $pluralModelLabel = 'الدورات';

    public static function getEloquentQuery(): Builder
    {
        return app(CourseService::class)->query();
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

                Textarea::make('short_description')
                    ->label('وصف مختصر'),

                RichEditor::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),

                Select::make('category_id')
                    ->label('التصنيف')
                    ->options(
                        Category::query()
                            ->where('type', CategoryType::Course->value)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('instructor_name')
                    ->label('اسم المدرب'),

                Select::make('type')
                    ->label('نوع الدورة')
                    ->options([
                        DeliveryType::Online->value => 'عن بعد',
                        DeliveryType::Offline->value => 'حضوري',
                    ])
                    ->live()
                    ->required(),

                Select::make('level')
                    ->label('المستوى')
                    ->options([
                        CourseLevel::Beginner->value => 'مبتدئ',
                        CourseLevel::Intermediate->value => 'متوسط',
                        CourseLevel::Advanced->value => 'متقدم',
                    ])
                    ->required(),

                TextInput::make('duration')
                    ->label('المدة (ساعات)')
                    ->numeric()
                    ->required(),

                TextInput::make('seats')
                    ->label('عدد المقاعد')
                    ->numeric()
                    ->nullable(),

                DatePicker::make('start_date')
                    ->label('تاريخ البداية'),

                DatePicker::make('end_date')
                    ->label('تاريخ النهاية'),

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
                    ->directory('courses'),

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

                TextColumn::make('level')
                    ->label('المستوى')
                    ->badge()
                    ->formatStateUsing(fn (CourseLevel $state) => match ($state) {
                        CourseLevel::Beginner => 'مبتدئ',
                        CourseLevel::Intermediate => 'متوسط',
                        CourseLevel::Advanced => 'متقدم',
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
                        DeliveryType::Online->value => 'عن بعد',
                        DeliveryType::Offline->value => 'حضوري',
                    ]),

                SelectFilter::make('level')
                    ->label('المستوى')
                    ->options([
                        CourseLevel::Beginner->value => 'مبتدئ',
                        CourseLevel::Intermediate->value => 'متوسط',
                        CourseLevel::Advanced->value => 'متقدم',
                    ]),

                SelectFilter::make('category_id')
                    ->label('التصنيف')
                    ->options(
                        Category::query()
                            ->where('type', CategoryType::Course->value)
                            ->pluck('name', 'id')
                    ),
            ])
            ->actions([
                Action::make('toggleStatus')
                    ->label('تبديل الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Course $record) {
                        $next = match ($record->status) {
                            PublishStatus::Draft => PublishStatus::Published,
                            PublishStatus::Published => PublishStatus::Archived,
                            PublishStatus::Archived => PublishStatus::Draft,
                        };

                        app(CourseService::class)->update($record->getKey(), ['status' => $next->value]);
                    }),

                EditAction::make()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف')
                    ->requiresConfirmation()
                    ->action(fn (Course $record) => app(CourseService::class)->delete($record->getKey())),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
