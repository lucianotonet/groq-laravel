<?php

namespace LucianoTonet\GroqLaravel;

use Illuminate\Support\ServiceProvider;
use LucianoTonet\GroqPHP\Groq;

/**
 * Provides the Groq service provider for the Laravel application.
 *
 * The GroqServiceProvider registers the Groq class with the application's service
 * container, allowing it to be resolved and used throughout the application. It
 * also publishes the default Groq configuration file to the application's config
 * directory, allowing the user to customize the configuration as needed.
 */
class GroqServiceProvider extends ServiceProvider
{
    /**
     * Registers the Groq service with the application's service container.
     *
     * This method binds the Groq class to the service container, allowing it to be
     * resolved and used throughout the application. The Groq instance is configured
     * with the API key and base URL specified in the application's environment.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(Groq::class, function ($app, $parameters = []) {
            return new Groq(
                $parameters['apiKey'] ?? env('GROQ_API_KEY'),
                array_merge($parameters['options'] ?? [], ['baseUrl' => env('GROQ_API_BASE', 'https://api.groq.com/openai/v1')])
            );
        });
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
        ]);
    }
}
