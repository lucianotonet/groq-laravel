<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use LucianoTonet\GroqLaravel\GroqLaravel;

class Groq extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GroqLaravel::class;
    }

    public static function chat()
    {
        return static::getFacadeRoot()->chat();
    }
}