<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Services\ActivityLogService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditActivityLog extends EditRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('حذف')
                ->requiresConfirmation()
                ->action(fn () => app(ActivityLogService::class)->delete($this->record->getKey()))
                ->successRedirectUrl(fn () => static::getResource()::getUrl('index')),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(ActivityLogService::class)->update($record->getKey(), $data);
    }
}
