<?php

namespace App\Filament\Pages;

use App\Enums\SettingGroup;
use App\Services\SettingService;
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

    protected static ?string $title = 'الإعدادات';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(app(SettingService::class)->getAllAsKeyValue());
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

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }
}
