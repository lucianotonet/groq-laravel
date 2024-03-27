<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Groq extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GroqLaravel';
    }
}

