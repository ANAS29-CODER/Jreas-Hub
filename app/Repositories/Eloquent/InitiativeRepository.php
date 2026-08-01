<?php

namespace App\Repositories\Eloquent;

use App\Models\Initiative;
use App\Repositories\Interfaces\InitiativeRepositoryInterface;

class InitiativeRepository extends BaseRepository implements InitiativeRepositoryInterface
{
    public function __construct(Initiative $model)
    {
        parent::__construct($model);
    }
}
