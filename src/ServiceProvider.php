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
        AboutCommand::add('devdot/user-artisan', fn () => [
            'available' => true,
        ]);

        // install all our commands
        $this->commands([
            Commands\ListUsers::class,
            Commands\DeleteUser::class,
            Commands\CreateUser::class,
        ]);
    }
}
