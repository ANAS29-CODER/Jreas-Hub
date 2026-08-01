<?php

namespace App\Repositories\Eloquent;

use App\Models\SiteGallery;
use App\Repositories\Interfaces\SiteGalleryRepositoryInterface;

class SiteGalleryRepository extends BaseRepository implements SiteGalleryRepositoryInterface
{
    public function __construct(SiteGallery $model)
    {
        parent::__construct($model);
    }
}
