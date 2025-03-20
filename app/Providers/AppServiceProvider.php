<?php

namespace App\Providers;


use ApiPlatform\State\ProcessorInterface;
use App\State\EventsStateProvider;
use ApiPlatform\State\ProviderInterface;
use App\State\TagsStateProvider;
use App\State\MobileEventsStateProvider;
use App\State\MobileEventsStateProcessor;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

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
    public function boot(UrlGenerator $url): void
    {
        if (env('FORCE_HTTPS', true)) { // Default value should be false for local server
            $url->forceScheme('https');
        }

        $this->app->tag(EventsStateProvider::class, ProviderInterface::class);
        $this->app->tag(TagsStateProvider::class, ProviderInterface::class);
        $this->app->tag(MobileEventsStateProvider::class, ProviderInterface::class);
        $this->app->tag(MobileEventsStateProcessor::class, ProcessorInterface::class);
    }
}
