<?php

namespace LucianoTonet\GroqLaravel;

use Illuminate\Support\ServiceProvider;
use LucianoTonet\GroqPHP\Groq;

class GroqServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__ . '/../config/groq.php', 'groq');

        $this->app->singleton(Groq::class, function ($app, $parameters = []) {
            return new Groq(
                $parameters['apiKey'] ?? config('groq.api_key'),
                array_merge($parameters['options'] ?? [], ['baseUrl' => config('groq.api_base', 'https://api.groq.com/openai/v1')])
            );
        });
        
        // Register the alias 'groq' -> Groq::class
        $this->app->alias(Groq::class, 'groq');

        // Register the GroqClient singleton
        $this->app->singleton(GroqClient::class, function ($app) {
            return new GroqClient(config('groq.api_key'));
        });

        // Register facade
        $this->app->alias(GroqClient::class, 'groq');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/groq.php' => config_path('groq.php'),
            ], 'config');
        }

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'groq');
    }
}