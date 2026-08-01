<?php

namespace App\Filament\Resources\SiteGalleryResource\Pages;

use App\Filament\Resources\SiteGalleryResource;
use App\Services\SiteGalleryService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSiteGallery extends EditRecord
{
    protected static string $resource = SiteGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('حذف')
                ->requiresConfirmation()
                ->action(fn () => app(SiteGalleryService::class)->delete($this->record->getKey()))
                ->successRedirectUrl(fn () => static::getResource()::getUrl('index')),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['image_path'] = is_array($data['image_path'])
            ? ($data['image_path'][0] ?? $record->image_path)
            : $data['image_path'];

        return app(SiteGalleryService::class)->update($record->getKey(), $data);
    }
}
