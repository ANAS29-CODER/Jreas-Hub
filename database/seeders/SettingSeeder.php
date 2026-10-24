<?php

namespace Database\Seeders;

use App\Enums\SettingGroup;
use App\Services\SettingService;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(SettingService::class);

        $service->saveMany([
            'site_name' => 'Jireas Hub',
            'site_description' => 'منصة جيرياس هب للدورات وورش العمل والمبادرات المجتمعية',
            'logo' => null,
            'favicon' => null,
        ], SettingGroup::General);

        $service->saveMany([
            'facebook_url' => null,
            'instagram_url' => null,
            'twitter_url' => null,
            'youtube_url' => null,
            'linkedin_url' => null,
            'whatsapp_number' => null,
        ], SettingGroup::Social);

        $service->saveMany([
            'contact_email' => 'info@jireashub.com',
            'contact_phone' => '',
            'contact_address' => '',
            'google_maps_link' => null,
        ], SettingGroup::Contact);

        $service->saveMany([
            'meta_title' => 'Jireas Hub',
            'meta_description' => 'منصة جيرياس هب للدورات وورش العمل والمبادرات المجتمعية',
            'google_analytics_id' => null,
        ], SettingGroup::Seo);

        $service->saveMany([
            'donation_mission_title' => 'Why Your Contribution Matters',
            'donation_mission_text' => '',
            'donation_stat_1_number' => '',
            'donation_stat_1_label' => 'Students Supported',
            'donation_stat_2_number' => '',
            'donation_stat_2_label' => 'Fully Sponsored Courses',
            'donation_stat_3_number' => '',
            'donation_stat_3_label' => 'Scholarship Funds Routed',
            'donation_stat_4_number' => '',
            'donation_stat_4_label' => 'Instructors Paid',
        ], SettingGroup::Donation);
    }
}
