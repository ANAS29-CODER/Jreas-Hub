<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.frontend', 'frontend.*'], function ($view) {
            // Cached on the request so the layout and every partial share one settings query.
            $attributes = request()->attributes;

            if (! $attributes->has('site_settings')) {
                $attributes->set('site_settings', app(SettingService::class)->getAllWithDefaults());
            }

            $settings = $attributes->get('site_settings');

            $view->with('settings', $settings);
            $view->with('donateUrl', $settings['donation_url']);
        });
    }
}
