<?php

namespace App\Filament\Widgets;

use App\Enums\PublishStatus;
use App\Services\DonationCampaignService;
use Filament\Widgets\Widget;

class DonationProgressWidget extends Widget
{
    protected static bool $isLazy = true;

    protected static string $view = 'filament.widgets.donation-progress-widget';

    protected function getCampaigns()
    {
        return app(DonationCampaignService::class)->query()
            ->where('status', PublishStatus::Published->value)
            ->latest('start_date')
            ->limit(5)
            ->get()
            ->map(function ($campaign) {
                $goal = (float) $campaign->goal_amount;
                $current = (float) $campaign->current_amount;

                $percentage = $goal > 0
                    ? min(100, round(($current / $goal) * 100, 1))
                    : 0;

                return [
                    'title' => $campaign->title,
                    'current_amount' => $current,
                    'goal_amount' => $goal,
                    'percentage' => $percentage,
                ];
            });
    }

    protected function getViewData(): array
    {
        return [
            'campaigns' => $this->getCampaigns(),
        ];
    }
}
