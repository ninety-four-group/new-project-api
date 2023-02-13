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

        $this->app->bind(
            'App\Contracts\SKUInterface',
            'App\Repositories\SKURepository',
        );


        $this->app->bind(
            'App\Contracts\VariationInterface',
            'App\Repositories\VariationRepository',
        );

        $this->app->bind(
            'App\Contracts\TagInterface',
            'App\Repositories\TagRepository',
        );

        $this->app->bind(
            'App\Contracts\CountryInterface',
            'App\Repositories\CountryRepository',
        );

        $this->app->bind(
            'App\Contracts\RegionInterface',
            'App\Repositories\RegionRepository',
        );

        $this->app->bind(
            'App\Contracts\CityInterface',
            'App\Repositories\CityRepository',
        );

        $this->app->bind(
            'App\Contracts\TownshipInterface',
            'App\Repositories\TownshipRepository',
        );

        $this->app->bind(
            'App\Contracts\CollectionInterface',
            'App\Repositories\CollectionRepository',
        );

        $this->app->bind(
            'App\Contracts\MediaInterface',
            'App\Repositories\MediaRepository',
        );

        $this->app->bind(
            'App\Contracts\ProductInterface',
            'App\Repositories\ProductRepository',
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
