<?php

namespace App\Providers;

use ApiPlatform\State\ProcessorInterface;
use App\State\EventsStateProvider;
use ApiPlatform\State\ProviderInterface;
use App\State\TagsStateProvider;
use App\State\MobileEventsStateProvider;
use App\State\MobileEventsStateProcessor;
use App\State\MobileRondesStateProvider;
use App\State\MobileNotificationsStateProvider;
use App\State\NotificationsStateProvider;
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
        if (env('FORCE_HTTPS', false)) { // Default value should be false for local server
            $url->forceScheme('https');
        }

        // API PROVIDERS
        $this->app->tag(EventsStateProvider::class, ProviderInterface::class);
        $this->app->tag(NotificationsStateProvider::class, ProviderInterface::class);
        $this->app->tag(TagsStateProvider::class, ProviderInterface::class);

        // APP PROVIDERS

        $this->app->tag(MobileEventsStateProvider::class, ProviderInterface::class);
        $this->app->tag(MobileNotificationsStateProvider::class, ProviderInterface::class);
        $this->app->tag(MobileRondesStateProvider::class, ProviderInterface::class);

        // APP PROCESSORS

        $this->app->tag(MobileEventsStateProcessor::class, ProcessorInterface::class);
    }
}
