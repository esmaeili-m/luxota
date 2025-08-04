<?php

namespace Modules\User\App\Providers;

use App\Observers\RelationLoggerObserver;
use Illuminate\Support\ServiceProvider;
use Modules\User\App\Models\User;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(
            \Modules\User\Repositories\UserRepository::class,
            \Modules\User\Repositories\UserRepository::class
        );

        $this->app->bind(
            \Modules\User\Services\UserService::class,
            \Modules\User\Services\UserService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        User::observe(RelationLoggerObserver::class);

    }
}
