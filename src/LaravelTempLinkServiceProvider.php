<?php
namespace RezafDev\LaravelTempLink;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use RezafDev\LaravelTempLink\Console\DeleteExpiredLinksCommand;

class LaravelTempLinkServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TempLink::class, function ($app){
            return new TempLink();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel_templink');
    }

    public function boot()
    {

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('templink.php'),
            ], 'laravel-temp-link-config');

            $this->commands([
                DeleteExpiredLinksCommand::class
            ]);

            if(config('laravel_templink.scheduler')){
                $this->app->booted(function () {
                    $schedule = $this->app->make(Schedule::class);
                    $schedule->command('templink:delete')->everyTenMinutes();
                });
            }

        }
    }
}