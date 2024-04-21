<?php

namespace LucianoTonet\GroqLaravel;

use Illuminate\Support\ServiceProvider;
use LucianoTonet\GroqPHP\Groq;

class GroqServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Groq::class, function ($app, $parameters) {
            return new Groq(
                $parameters['apiKey'] ?? env('GROQ_API_KEY'),
                $parameters['options'] ?? []
            );
        });
    }
}

