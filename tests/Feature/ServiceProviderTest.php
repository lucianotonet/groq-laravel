<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use Orchestra\Testbench\TestCase;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqPHP\Groq;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadEnvironmentVariables();
    }

    protected function loadEnvironmentVariables()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
    }

    public function testServiceProviderIsRegistered()
    {
        $this->assertInstanceOf(Groq::class, $this->app->make(Groq::class));
    }
}