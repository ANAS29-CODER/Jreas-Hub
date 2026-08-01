<?php

namespace App\Filament\Resources\DonationCampaignResource\Pages;

use App\Filament\Resources\DonationCampaignResource;
use App\Services\DonationCampaignService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDonationCampaign extends EditRecord
{
    protected static string $resource = DonationCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('حذف')
                ->requiresConfirmation()
                ->action(fn () => app(DonationCampaignService::class)->delete($this->record->getKey()))
                ->successRedirectUrl(fn () => static::getResource()::getUrl('index')),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(DonationCampaignService::class)->update($record->getKey(), $data);
    }
}
