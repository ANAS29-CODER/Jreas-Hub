<?php

namespace App\Repositories\Eloquent;

use App\Models\Workshop;
use App\Repositories\Interfaces\WorkshopRepositoryInterface;

class WorkshopRepository extends BaseRepository implements WorkshopRepositoryInterface
{
    public function __construct(Workshop $model)
    {
        parent::__construct($model);
    }
}
