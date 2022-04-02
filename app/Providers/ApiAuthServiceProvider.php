<?php

namespace App\Providers;

use App\Services\Api\V1\AdminService;
use App\Services\Api\V1\AuthTypeServicesRegistry;
use App\Services\Api\V1\SupplierService;
use App\Services\Api\V1\UserService;
use Illuminate\Support\ServiceProvider;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthTypeServicesRegistry::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(AuthTypeServicesRegistry::class)
            ->register("user", new UserService());

        $this->app->make(AuthTypeServicesRegistry::class)
            ->register("admin", new AdminService());
    }
}
