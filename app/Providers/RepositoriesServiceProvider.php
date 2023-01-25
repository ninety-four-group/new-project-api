<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\AdminInterface',
            'App\Repositories\AdminRepository',
        );

        $this->app->bind(
            'App\Contracts\UserInterface',
            'App\Repositories\UserRepository',
        );

        $this->app->bind(
            'App\Contracts\CategoryInterface',
            'App\Repositories\CategoryRepository',
        );

        $this->app->bind(
            'App\Contracts\BrandInterface',
            'App\Repositories\BrandRepository',
        );

        $this->app->bind(
            'App\Contracts\WarehouseInterface',
            'App\Repositories\WarehouseRepository',
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
