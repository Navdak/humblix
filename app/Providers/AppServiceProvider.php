<?php
namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('globalSettings', Cache::remember('site_settings_public', 3600, function () {
                try {
                    return SiteSetting::query()->pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            }));
        });
    }
}
