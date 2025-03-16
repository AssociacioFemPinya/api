<?php

namespace App\Providers;


use ApiPlatform\State\ProcessorInterface;
use App\State\EventsStateProvider;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Laravel\Eloquent\State\PersistProcessor;
use ApiPlatform\Laravel\Eloquent\State\RemoveProcessor;
use App\State\TagsStateProvider;
use App\State\MobileEventsStateProvider;
use App\State\MobileEventsStateProcessor;
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
        $this->app->tag(MobileEventsStateProvider::class, ProviderInterface::class);
        $this->app->tag(MobileEventsStateProcessor::class, [PersistProcessor::class, RemoveProcessor::class,ProcessorInterface::class]);
    }
}
