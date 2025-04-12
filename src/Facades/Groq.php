<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \LucianoTonet\GroqPHP\Chat chat()
 * @method static \LucianoTonet\GroqPHP\Models models()
 * @method static \LucianoTonet\GroqPHP\Vision vision()
 * @method static \LucianoTonet\GroqPHP\Audio audio()
 * @method static \LucianoTonet\GroqPHP\Files files()
 * @method static \LucianoTonet\GroqPHP\Batches batches()
 * @method static void setConfig(array $options)
 * @method static void setOptions(array $options)
 * @method static \LucianoTonet\GroqPHP\Groq getClient()
 *
 * @see \LucianoTonet\GroqLaravel\GroqClient
 */
class Groq extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'groq';
    }
}