<?php

namespace App\Providers;

use App\Helpers\Helper;
use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
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

        try {
            if (Schema::hasTable('settings')) {
                $this->app->singleton('settings', function ($app) {
                    $settingsArray = Cache::rememberForever('app_settings', function () {
                        if (Schema::hasTable('settings')) {
                            $data = Setting::first();
                            return $data ? $data->toArray() : []; 
                        }
                        return [];
                    });
                    return (object) $settingsArray; 
                });
                
                $appSettings = Setting::first();
                View::share('appSettings', $appSettings);
                View::share('settings', $appSettings);

                if ($appSettings) {
                    if ($appSettings->mail_host) {
                        Config::set('mail.default', $appSettings->mail_mailer ?? 'smtp');
                        Config::set('mail.mailers.smtp.host', $appSettings->mail_host);
                        Config::set('mail.mailers.smtp.port', $appSettings->mail_port);
                        Config::set('mail.mailers.smtp.username', $appSettings->mail_username);
                        Config::set('mail.mailers.smtp.password', $appSettings->mail_password);
                        Config::set('mail.mailers.smtp.encryption', $appSettings->mail_encryption);
                        Config::set('mail.from.address', $appSettings->mail_from_address);
                        Config::set('mail.from.name', $appSettings->mail_from_name);
                    }

                    if ($appSettings->imap_host) {
                        Config::set('imap.accounts.default.host', $appSettings->imap_host);
                    }
                    if ($appSettings->imap_protocol) {
                        Config::set('imap.accounts.default.protocol', $appSettings->imap_protocol);
                        if (!$appSettings->imap_port) {
                            $defaultPort = ($appSettings->imap_protocol === 'pop3') ? 995 : 993;
                            Config::set('imap.accounts.default.port', $defaultPort);
                        }
                        if (!$appSettings->imap_encryption) {
                            Config::set('imap.accounts.default.encryption', 'ssl');
                        }
                    }
                    if ($appSettings->imap_port) {
                        Config::set('imap.accounts.default.port', $appSettings->imap_port);
                    }
                    if ($appSettings->imap_encryption) {
                        Config::set('imap.accounts.default.encryption', $appSettings->imap_encryption);
                    }
                    if ($appSettings->imap_username) {
                        Config::set('imap.accounts.default.username', $appSettings->imap_username);
                    }
                    if ($appSettings->imap_password) {
                        Config::set('imap.accounts.default.password', $appSettings->imap_password);
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore if DB not ready
        }
    }
}
