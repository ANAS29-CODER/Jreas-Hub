<?php

namespace App\Filament\Resources\DonationCampaignResource\Pages;

use App\Filament\Resources\DonationCampaignResource;
use App\Services\DonationCampaignService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDonationCampaign extends CreateRecord
{
    protected static string $resource = DonationCampaignResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(DonationCampaignService::class)->create($data);
    }
}
