<?php

namespace App\Providers;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
    }
}
