<?php

namespace Gashey\MobiverseUssd;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class UssdServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $publishPath = config_path('ussd.php');
        } else {
            $publishPath = base_path('config/ussd.php');
        }
        $this->publishes([$this->configPath() => $publishPath], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'ussd');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/ussd.php';
    }
}
