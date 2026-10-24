<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;

class DonationController extends Controller
{
    public function index()
    {
        $campaigns = DonationCampaign::query()
            ->where('status', PublishStatus::Published->value)
            ->orderByDesc('start_date')
            ->get();

        return view('frontend.donation.index', compact('campaigns'));
    }

    public function show(DonationCampaign $campaign)
    {
        abort_unless($campaign->status === PublishStatus::Published, 404);

        return view('frontend.donation.show', compact('campaign'));
    }
}
