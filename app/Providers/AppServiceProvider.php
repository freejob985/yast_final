<?php

namespace App\Providers;

use App\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // fix for specific key too long error for MySQL v5.7.7 or lower
        Schema::defaultStringLength(191);
          resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');

         
 
        if(site_already_installed() && !$this->app->runningInConsole())
        {

            /**
             * Clear view cache first before doing upgrade.
             */
             
             
            if(Request::is('update'))
            {
                Artisan::call('cache:clear');
                Artisan::call('route:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
            }

            // SHARE TO ALL ROUTES
            $site_global_settings  = Setting::find(1);
            view()->share('site_global_settings', $site_global_settings);

            // config SMTP
            $this->configSMTP(
                $site_global_settings->settings_site_smtp_enabled,
                $site_global_settings->settings_site_smtp_sender_name,
                $site_global_settings->settings_site_smtp_sender_email,
                $site_global_settings->settings_site_smtp_host,
                $site_global_settings->settings_site_smtp_port,
                $site_global_settings->settings_site_smtp_encryption,
                $site_global_settings->settings_site_smtp_username,
                $site_global_settings->settings_site_smtp_password
            );

            // set site language
            App::setlocale(empty($site_global_settings->setting_site_language) ? Setting::LANGUAGE_EN : $site_global_settings->setting_site_language);

        }
    }

    private function configSMTP($smtp_enabled, $from_name, $from_email,
                                $smtp_host, $smtp_port, $smtp_encryption,
                                $smtp_username, $smtp_password)
    {
        if($smtp_enabled)
        {
            $encryption = null;
            if($smtp_encryption == Setting::SITE_SMTP_ENCRYPTION_SSL)
            {
                $encryption = Setting::SITE_SMTP_ENCRYPTION_SSL_STR;
            }
            elseif($smtp_encryption == Setting::SITE_SMTP_ENCRYPTION_TLS)
            {
                $encryption = Setting::SITE_SMTP_ENCRYPTION_TLS_STR;
            }

            config([
                'mail.host' => $smtp_host,
                'mail.port' => $smtp_port,
                'mail.from' => ['address' => $from_email, 'name' => $from_name],
                'mail.encryption' => $encryption,
                'mail.username' => $smtp_username,
                'mail.password' => $smtp_password,
            ]);
        }
    }
}
