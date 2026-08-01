<?php

namespace App\Filament\Resources\SiteGalleryResource\Pages;

use App\Filament\Resources\SiteGalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteGalleries extends ListRecords
{
    protected static string $resource = SiteGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
