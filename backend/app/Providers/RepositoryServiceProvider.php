<?php

namespace App\Providers;

use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryContract;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProductRepository::class);
        $this->app->singleton(OrderRepository::class);
        $this->app->singleton(CategoryRepository::class);

        $this->app->bind(BaseRepositoryContract::class, BaseRepository::class);
    }
}
