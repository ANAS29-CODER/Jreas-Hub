<?php

namespace App\Repositories\Eloquent;

use App\Models\Training;
use App\Repositories\Interfaces\TrainingRepositoryInterface;

class TrainingRepository extends BaseRepository implements TrainingRepositoryInterface
{
    public function __construct(Training $model)
    {
        parent::__construct($model);
    }
}
