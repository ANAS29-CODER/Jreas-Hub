<?php

namespace App\Filament\Resources\InitiativeResource\Pages;

use App\Filament\Resources\InitiativeResource;
use App\Services\InitiativeService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditInitiative extends EditRecord
{
    protected static string $resource = InitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('حذف')
                ->requiresConfirmation()
                ->action(fn () => app(InitiativeService::class)->delete($this->record->getKey()))
                ->successRedirectUrl(fn () => static::getResource()::getUrl('index')),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(InitiativeService::class)->update($record->getKey(), $data);
    }
}
