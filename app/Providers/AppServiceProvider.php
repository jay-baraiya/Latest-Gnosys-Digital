<?php

namespace App\Providers;

use App\Helpers\Helper;
use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Gate::before(function ($user, $ability) {
            $userPermissions = Helper::getPermissions();

            if (in_array($ability, $userPermissions)) {
                return true;
            }

            return null;
        });

        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');

        // અહીં આપણે કન્ડિશન મૂકી છે જેથી જ્યારે કમાન્ડ લાઇન (CLI) રન થતી હોય
        // અથવા ટેબલ ન હોય ત્યારે એરર ના આવે.
        if (!app()->runningInConsole() && Schema::hasTable('settings')) {
            $settings = Setting::query()->first();
            View::share('settings', $settings);
        }
    }
}
