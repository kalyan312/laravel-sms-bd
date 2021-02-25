<?php

namespace Khbd\LaravelSmsBD;

use Khbd\LaravelSmsBD\Console\MakeGatewayCommand;
use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/sms.php' => config_path('sms.php'),
            __DIR__.'/Database/Migrations' => database_path('migrations'),
        ], 'sms');

        $this->app->singleton(SMS::class, function () {
            return new SMS();
        });

        $this->app->alias(SMS::class, 'sms');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeGatewayCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/sms.php',
            'sms'
        );
    }
}
