<?php

namespace Syriaweb\Fattelettronica;

use Illuminate\Support\ServiceProvider;

class FattelettronicaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->publishes([
//            __DIR__.'/Config/fattelettronica.php' => config_path('fattelettronica.php'),
//        ]);

        $configPath = __DIR__ . '/Config/fattelettronica.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'Config');

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes/web.php';
        $this->app->make('Syriaweb\Fattelettronica\FattelettronicaController');
    }

    protected function getConfigPath()
    {
        return config_path('fattelettronica.php');
    }
}
