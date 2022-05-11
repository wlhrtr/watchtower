<?php

namespace Wlhrtr\Watchtower;

use Illuminate\Support\ServiceProvider;
use Wlhrtr\Watchtower\Commands\NotifyWatchtowerCommand;
use Wlhrtr\Watchtower\Services\WatchtowerService;

class WatchtowerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WatchtowerService::class, function () {
            return new WatchtowerService(
                config('watchtower.url'),
                config('watchtower.client_id'),
                config('watchtower.client_secret')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerConfig();
    }

    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) return;

        $this->commands([
            NotifyWatchtowerCommand::class, // make:livewire
        ]);
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/watchtower.php', 'watchtower');
    }
}
