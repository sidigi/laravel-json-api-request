<?php

namespace Sidigi\LaravelJsonApiRequest\Tests;

use MohammedManssour\FormRequestTester\TestsFormRequests;
use Orchestra\Testbench\TestCase as OrchestralTestCase;
use Sidigi\LaravelJsonApiRequest\LaravelJsonApiRequestProvider;

class TestCase extends OrchestralTestCase
{
    use TestsFormRequests;

    protected function getPackageProviders($app)
    {
        return [
            LaravelJsonApiRequestProvider::class,
        ];
    }
}
