<?php namespace Rolice\LangSync;

use Illuminate\Support\ServiceProvider;

class LangSyncServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('rolice/lang-sync');

        $this->app->bind('lang.labels.extract', 'Rolice\LangSync\CommandManager');
        $this->commands(['lang.labels.extract']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'Rolice\LangSync\CommandManager' ];
    }

}
