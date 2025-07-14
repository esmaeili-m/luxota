<?php

namespace App\Providers;

use App\Observers\RoleObserver;
use Illuminate\Support\ServiceProvider;
use Modules\Role\App\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
