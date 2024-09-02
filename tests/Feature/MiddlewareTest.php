<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Illuminate\Http\Request;
use LucianoTonet\GroqLaravel\Middleware\GroqRateLimiter;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class MiddlewareTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\LucianoTonet\GroqLaravel\GroqServiceProvider::class];
    }

    public function testRateLimiterMiddleware()
    {
        RateLimiter::shouldReceive('tooManyAttempts')->andReturn(false);
        RateLimiter::shouldReceive('hit')->once();
        Log::shouldReceive('warning')->never();

        $request = Request::create('/test', 'GET');
        $middleware = new GroqRateLimiter();

        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRateLimiterMiddlewareExceedsLimit()
    {
        RateLimiter::shouldReceive('tooManyAttempts')->andReturn(true);
        Log::shouldReceive('warning')->once();

        $request = Request::create('/test', 'GET');
        $middleware = new GroqRateLimiter();

        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        $this->assertEquals(429, $response->getStatusCode());
    }
}