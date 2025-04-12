<?php

namespace LucianoTonet\GroqLaravel;

use Illuminate\Support\ServiceProvider;
use LucianoTonet\GroqPHP\Groq;

class GroqServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Groq::class, function ($app, $parameters = []) {
            return new Groq(
                $parameters['apiKey'] ?? config('groq.api_key'),
                array_merge($parameters['options'] ?? [], ['baseUrl' => config('groq.api_base', 'https://api.groq.com/openai/v1')])
            );
        });
        
        // Register the alias 'groq' -> Groq::class
        $this->app->alias(Groq::class, 'groq');
    }

    /**
     * Publishes the Groq configuration file to the application's config directory.
     *
     * This method is called during the package's boot process. It allows the user to
     * publish the default Groq configuration file to their application, so they can
     * customize the configuration as needed.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/groq.php' => config_path('groq.php'),
        ], 'config');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'groq');
    }
}