<?php

namespace App\Providers;

use App\State\EventsStateProvider;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\State\Provider\ParameterProvider;
use App\State\AbstractStateProvider;
use ApiPlatform\State\ProviderInterface;
use App\ParameterProvers\CollaParameterProvider;
use App\State\AbstractStateProcessor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->tag([CollaParameterProvider::class], ParameterProvider::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        //$this->app->tag(AbstractStateProcessor::class, ProcessorInterface::class);

		//$this->app->tag(AbstractStateProvider::class, ProviderInterface::class);

		$this->app->tag(EventsStateProvider::class, ProviderInterface::class);


    }
}
