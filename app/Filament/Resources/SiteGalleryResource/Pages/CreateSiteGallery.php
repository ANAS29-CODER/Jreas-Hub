<?php

namespace App\Filament\Resources\SiteGalleryResource\Pages;

use App\Filament\Resources\SiteGalleryResource;
use App\Services\SiteGalleryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSiteGallery extends CreateRecord
{
    protected static string $resource = SiteGalleryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $service = app(SiteGalleryService::class);
        $paths = (array) $data['image_path'];

        $records = collect($paths)->map(fn (string $path) => $service->create([
            ...$data,
            'image_path' => $path,
        ]));

        return $records->first();
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
