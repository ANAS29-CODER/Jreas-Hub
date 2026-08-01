<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Services\ActivityLogService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateActivityLog extends CreateRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(ActivityLogService::class)->create($data);
    }
}
