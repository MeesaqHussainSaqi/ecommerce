<?php

namespace App\Providers;

use App\Repositories\BaseInterface;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(\App\Repositories\Auth\AuthInterface::class, \App\Repositories\Auth\AuthRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}