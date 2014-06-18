<?php namespace BigName\EventDispatcher\Integrations\Laravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('BigName\EventDispatcher\Containers\Container', 'BigName\EventDispatcher\Containers\LaravelContainer');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['BigName\EventDispatcher\Containers\Container'];
    }
}