<?php

namespace LucianoTonet\GroqLaravel;

use Illuminate\Support\ServiceProvider;
use LucianoTonet\GroqPHP\Groq;

class GroqServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Groq', function () {
            return new Groq();
        });
    }

    public function boot()
    {
        //
    }
}

