<?php

namespace App\Providers;

use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\MessageRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( MessageRepositoryInterface::class, MessageRepository::class );
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
