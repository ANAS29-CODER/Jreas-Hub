<?php

namespace App\Filament\Resources\InitiativeResource\Pages;

use App\Filament\Resources\InitiativeResource;
use App\Services\InitiativeService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateInitiative extends CreateRecord
{
    protected static string $resource = InitiativeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(InitiativeService::class)->create($data);
    }
}
