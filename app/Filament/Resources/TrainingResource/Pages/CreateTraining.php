<?php

namespace App\Filament\Resources\TrainingResource\Pages;

use App\Filament\Resources\TrainingResource;
use App\Services\TrainingService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTraining extends CreateRecord
{
    protected static string $resource = TrainingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(TrainingService::class)->create($data);
    }
}
