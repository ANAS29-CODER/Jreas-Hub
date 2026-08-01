<?php

namespace App\Repositories\Eloquent;

use App\Models\DonationCampaign;
use App\Repositories\Interfaces\DonationCampaignRepositoryInterface;

class DonationCampaignRepository extends BaseRepository implements DonationCampaignRepositoryInterface
{
    public function __construct(DonationCampaign $model)
    {
        parent::__construct($model);
    }
}
