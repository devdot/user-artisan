<?php

namespace Devdot\UserArtisan;

use Illuminate\Foundation\Console\AboutCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        AboutCommand::add('devdot/artisan-user', fn () => [
            'available' => true,
        ]);

        // install all our commands
        if($this->app->runningInConsole()) {
            $this->commands([
                Commands\ListUsers::class,
            ]);
        }
    }
}
