<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest;

use Illuminate\Support\ServiceProvider;

class LaravelJsonApiRequestProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json-api-request.php', 'json-api-request');
    }

    protected function registerPublishables(): self
    {
        $this->publishes([
            __DIR__.'/../config/json-api-request.php' => config_path('json-api-request.php'),
        ], 'config');

        return $this;
    }
}
