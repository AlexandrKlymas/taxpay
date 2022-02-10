<?php namespace EvolutionCMS\Pwa;

use EvolutionCMS\ServiceProvider;

class PwaServiceProvider extends ServiceProvider
{
    protected $namespace = 'pwa';

    public function boot()
    {
        //add custom routes for package
        include(__DIR__.'/Http/routes.php');

        //Custom Views
        $this->loadViewsFrom(__DIR__ . '/../views', 'pwa');

        //For use config
        $this->mergeConfigFrom(__DIR__ . '/../config/pwa.php', 'pwa');

    }

}