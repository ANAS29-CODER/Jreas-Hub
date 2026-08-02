<?php

namespace App\Filament\Widgets;

use App\Enums\PublishStatus;
use App\Services\CourseService;
use App\Services\DonationCampaignService;
use App\Services\InitiativeService;
use App\Services\TrainingService;
use App\Services\WorkshopService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $courses = app(CourseService::class)->query()
            ->where('status', PublishStatus::Published->value)
            ->count();

        $workshops = app(WorkshopService::class)->query()
            ->where('status', PublishStatus::Published->value)
            ->count();

        $initiatives = app(InitiativeService::class)->query()
            ->where('status', PublishStatus::Published->value)
            ->count();

        $trainings = app(TrainingService::class)->query()
            ->where('status', PublishStatus::Published->value)
            ->count();

        $donationCampaigns = app(DonationCampaignService::class)->query()
            ->where('status', PublishStatus::Published->value)
            ->count();

        return [
            Stat::make('الدورات المنشورة', $courses)
                ->icon('heroicon-o-academic-cap'),

            Stat::make('ورش العمل المنشورة', $workshops)
                ->icon('heroicon-o-presentation-chart-line'),

            Stat::make('المبادرات المنشورة', $initiatives)
                ->icon('heroicon-o-light-bulb'),

            Stat::make('التدريبات المنشورة', $trainings)
                ->icon('heroicon-o-briefcase'),

            Stat::make('حملات التبرع النشطة', $donationCampaigns)
                ->icon('heroicon-o-heart'),
        ];
    }
}
