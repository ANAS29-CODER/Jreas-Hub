<?php

namespace App\Providers;

use App\Repositories\Eloquent\ActivityLogRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\DonationCampaignRepository;
use App\Repositories\Eloquent\InitiativeRepository;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\PageRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\SiteGalleryRepository;
use App\Repositories\Eloquent\TrainingRepository;
use App\Repositories\Eloquent\WorkshopRepository;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\DonationCampaignRepositoryInterface;
use App\Repositories\Interfaces\InitiativeRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\SiteGalleryRepositoryInterface;
use App\Repositories\Interfaces\TrainingRepositoryInterface;
use App\Repositories\Interfaces\WorkshopRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(WorkshopRepositoryInterface::class, WorkshopRepository::class);
        $this->app->bind(InitiativeRepositoryInterface::class, InitiativeRepository::class);
        $this->app->bind(TrainingRepositoryInterface::class, TrainingRepository::class);
        $this->app->bind(DonationCampaignRepositoryInterface::class, DonationCampaignRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(SiteGalleryRepositoryInterface::class, SiteGalleryRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
    }
}
