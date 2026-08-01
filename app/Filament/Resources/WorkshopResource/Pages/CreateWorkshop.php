<?php

namespace App\Filament\Resources\WorkshopResource\Pages;

use App\Filament\Resources\WorkshopResource;
use App\Services\WorkshopService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWorkshop extends CreateRecord
{
    protected static string $resource = WorkshopResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(WorkshopService::class)->create($data);
    }
}
