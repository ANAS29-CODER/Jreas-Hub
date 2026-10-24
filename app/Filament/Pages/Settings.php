<?php

namespace App\Filament\Pages;

use App\Enums\SettingGroup;
use App\Services\SettingService;
use App\Support\YouTube;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'الإعدادات';

    protected static ?string $navigationGroup = 'الإعدادات';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'الإعدادات';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(app(SettingService::class)->getAllWithDefaults());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('settings')
                    ->tabs([
                        Tab::make('عام')
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('اسم الموقع'),

                                Textarea::make('site_description')
                                    ->label('وصف الموقع'),

                                FileUpload::make('logo')
                                    ->label('الشعار')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings'),

                                FileUpload::make('favicon')
                                    ->label('أيقونة الموقع')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings'),
                            ]),

                        Tab::make('التواصل الاجتماعي')
                            ->schema([
                                TextInput::make('facebook_url')
                                    ->label('رابط فيسبوك')
                                    ->nullable(),

                                TextInput::make('instagram_url')
                                    ->label('رابط انستغرام')
                                    ->nullable(),

                                TextInput::make('twitter_url')
                                    ->label('رابط تويتر')
                                    ->nullable(),

                                TextInput::make('youtube_url')
                                    ->label('رابط يوتيوب')
                                    ->nullable(),

                                TextInput::make('linkedin_url')
                                    ->label('رابط لينكد إن')
                                    ->nullable(),

                                TextInput::make('whatsapp_number')
                                    ->label('رقم واتساب')
                                    ->nullable(),
                            ]),

                        Tab::make('التواصل')
                            ->schema([
                                TextInput::make('contact_email')
                                    ->label('البريد الإلكتروني'),

                                TextInput::make('contact_phone')
                                    ->label('رقم الهاتف'),

                                Textarea::make('contact_address')
                                    ->label('العنوان'),

                                TextInput::make('google_maps_link')
                                    ->label('رابط خرائط جوجل')
                                    ->nullable(),
                            ]),

                        Tab::make('تحسين محركات البحث')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('عنوان الميتا'),

                                Textarea::make('meta_description')
                                    ->label('وصف الميتا'),

                                TextInput::make('google_analytics_id')
                                    ->label('معرف جوجل أناليتكس')
                                    ->nullable(),
                            ]),

                        Tab::make('صفحة التبرعات')
                            ->schema([
                                Fieldset::make('رأس الصفحة')
                                    ->schema([
                                        TextInput::make('donation_page_title')
                                            ->label('عنوان الصفحة'),

                                        Textarea::make('donation_meta_description')
                                            ->label('وصف الصفحة لمحركات البحث')
                                            ->rows(2),

                                        FileUpload::make('donation_banner_image')
                                            ->label('صورة الغلاف')
                                            ->helperText('تظهر خلف عنوان الصفحة، في صفحة التبرعات وصفحة كل حملة. يُفضّل صورة عريضة. اتركها فارغة لاستخدام الصورة الحالية.')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->columnSpanFull(),
                                    ]),

                                Fieldset::make('قسم الرسالة والإحصائيات')
                                    ->schema([
                                        TextInput::make('donation_mission_title')
                                            ->label('العنوان')
                                            ->helperText('يظهر القسم عند تعبئة العنوان أو الإحصائية الأولى'),

                                        Textarea::make('donation_mission_text')
                                            ->label('النص')
                                            ->rows(4),

                                        ...collect(range(1, 4))->flatMap(fn (int $i) => [
                                            TextInput::make("donation_stat_{$i}_number")
                                                ->label("الإحصائية {$i} - الرقم"),

                                            TextInput::make("donation_stat_{$i}_label")
                                                ->label("الإحصائية {$i} - الوصف"),
                                        ])->all(),
                                    ]),

                                Fieldset::make('قسم الحملات')
                                    ->schema([
                                        TextInput::make('donation_campaigns_title')
                                            ->label('عنوان القسم'),

                                        TextInput::make('donation_campaigns_empty')
                                            ->label('النص عند عدم وجود حملات'),

                                        TextInput::make('donation_button_text')
                                            ->label('نص زر التبرع في الحملات'),

                                        TextInput::make('donation_url')
                                            ->label('رابط زر التبرع')
                                            ->helperText('يُستخدم في زر Donate أعلى الموقع وفي صفحة كل حملة')
                                            ->url()
                                            ->nullable(),
                                    ]),
                            ]),

                        Tab::make('الصفحة الرئيسية')
                            ->schema([
                                Fieldset::make('القسم الرئيسي (Hero)')
                                    ->schema([
                                        TextInput::make('home_hero_badge')
                                            ->label('الشارة'),

                                        TextInput::make('home_hero_title')
                                            ->label('العنوان'),

                                        TextInput::make('home_hero_highlight')
                                            ->label('الجزء الملوّن من العنوان'),

                                        Textarea::make('home_hero_text')
                                            ->label('النص')
                                            ->rows(3),

                                        FileUpload::make('home_hero_image')
                                            ->label('صورة الخلفية')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings'),
                                    ]),

                                Fieldset::make('بطاقات المميزات')
                                    ->schema(collect(range(1, 4))->flatMap(fn (int $i) => [
                                        TextInput::make("home_feature_{$i}_title")
                                            ->label("البطاقة {$i} - العنوان"),

                                        TextInput::make("home_feature_{$i}_text")
                                            ->label("البطاقة {$i} - النص"),
                                    ])->all()),

                                Fieldset::make('قسم من نحن')
                                    ->schema([
                                        TextInput::make('home_about_title')
                                            ->label('العنوان'),

                                        TextInput::make('home_about_button')
                                            ->label('نص الزر'),

                                        Textarea::make('home_about_text')
                                            ->label('النص')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Textarea::make('home_about_points')
                                            ->label('النقاط (سطر لكل نقطة)')
                                            ->rows(4)
                                            ->columnSpanFull(),

                                        TextInput::make('home_about_video_url')
                                            ->label('رابط فيديو يوتيوب')
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->helperText('اتركه فارغاً لإظهار الصورة فقط بدون زر تشغيل')
                                            ->url()
                                            ->rule(fn () => function (string $attribute, $value, \Closure $fail) {
                                                if (filled($value) && YouTube::id($value) === null) {
                                                    $fail('الرابط ليس رابط فيديو يوتيوب صحيح.');
                                                }
                                            }),

                                        FileUpload::make('home_about_video_cover')
                                            ->label('صورة الغلاف')
                                            ->helperText('اختياري. إذا تُركت فارغة تُستخدم صورة الفيديو من يوتيوب')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings'),
                                    ]),

                                Fieldset::make('بطاقات الخدمات')
                                    ->schema(collect([
                                        'courses' => 'الدورات',
                                        'workshops' => 'ورش العمل',
                                        'initiatives' => 'المبادرات',
                                        'trainings' => 'التدريب الجامعي',
                                    ])->flatMap(fn (string $label, string $key) => [
                                        TextInput::make("home_service_{$key}_text")
                                            ->label("{$label} - الوصف"),

                                        FileUpload::make("home_service_{$key}_image")
                                            ->label("{$label} - الصورة")
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings'),
                                    ])->values()->all()),

                                Fieldset::make('قسم البانر')
                                    ->schema([
                                        TextInput::make('home_banner_label')
                                            ->label('النص الصغير'),

                                        TextInput::make('home_banner_title')
                                            ->label('العنوان'),

                                        Textarea::make('home_banner_text')
                                            ->label('النص')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        ...collect(range(1, 3))->flatMap(fn (int $i) => [
                                            TextInput::make("home_stat_{$i}_number")
                                                ->label("الإحصائية {$i} - الرقم"),

                                            TextInput::make("home_stat_{$i}_label")
                                                ->label("الإحصائية {$i} - الوصف"),
                                        ])->all(),

                                        FileUpload::make('home_banner_image')
                                            ->label('صورة البانر')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings'),
                                    ]),
                            ]),

                        Tab::make('صفحة من نحن')
                            ->schema([
                                TextInput::make('about_heading')
                                    ->label('العنوان الرئيسي')
                                    ->helperText('الوصف يُعدَّل من قسم "الصفحات" ← من نحن'),

                                Textarea::make('about_points')
                                    ->label('النقاط (سطر لكل نقطة)')
                                    ->rows(3),

                                FileUpload::make('about_image')
                                    ->label('الصورة')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings'),

                                Fieldset::make('ما يميزنا')
                                    ->schema([
                                        TextInput::make('about_features_title')
                                            ->label('عنوان القسم'),

                                        Textarea::make('about_features_text')
                                            ->label('وصف القسم')
                                            ->rows(2),

                                        ...collect(range(1, 4))->flatMap(fn (int $i) => [
                                            TextInput::make("about_card_{$i}_title")
                                                ->label("البطاقة {$i} - العنوان"),

                                            Textarea::make("about_card_{$i}_text")
                                                ->label("البطاقة {$i} - النص")
                                                ->rows(2),
                                        ])->all(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $service = app(SettingService::class);

        $service->saveMany([
            'site_name' => $data['site_name'] ?? null,
            'site_description' => $data['site_description'] ?? null,
            'logo' => $data['logo'] ?? null,
            'favicon' => $data['favicon'] ?? null,
        ], SettingGroup::General);

        $service->saveMany([
            'facebook_url' => $data['facebook_url'] ?? null,
            'instagram_url' => $data['instagram_url'] ?? null,
            'twitter_url' => $data['twitter_url'] ?? null,
            'youtube_url' => $data['youtube_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'whatsapp_number' => $data['whatsapp_number'] ?? null,
        ], SettingGroup::Social);

        $service->saveMany([
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_address' => $data['contact_address'] ?? null,
            'google_maps_link' => $data['google_maps_link'] ?? null,
        ], SettingGroup::Contact);

        $service->saveMany([
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'google_analytics_id' => $data['google_analytics_id'] ?? null,
        ], SettingGroup::Seo);

        $service->saveMany(self::pick($data, 'donation_'), SettingGroup::Donation);

        $service->saveMany(self::pick($data, 'home_'), SettingGroup::Home);

        $service->saveMany(self::pick($data, 'about_'), SettingGroup::About);

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }

    /**
     * All form values whose key starts with $prefix (the Home and About tabs are prefixed this way).
     */
    private static function pick(array $data, string $prefix): array
    {
        return array_filter($data, fn (string $key) => str_starts_with($key, $prefix), ARRAY_FILTER_USE_KEY);
    }
}
