<?php

namespace App\Providers;

use App\State\EventsStateProvider;
use ApiPlatform\State\ProviderInterface;
use App\State\TagsStateProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->app->tag(EventsStateProvider::class, ProviderInterface::class);
        $this->app->tag(TagsStateProvider::class, ProviderInterface::class);


    }
}
