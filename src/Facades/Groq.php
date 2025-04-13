<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use LucianoTonet\GroqPHP\Groq as GroqPHP;

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
    
    /**
     * Get transcriptions instance for converting audio to text.
     *
     * @return \LucianoTonet\GroqPHP\Transcriptions An instance of the Transcriptions class.
     * @throws GroqException
     */
    public static function transcriptions(): \LucianoTonet\GroqPHP\Transcriptions
    {
        return app(GroqPHP::class)->audio()->transcriptions();
    }
    
    /**
     * Get translations instance for translating audio to text in different languages.
     *
     * @return \LucianoTonet\GroqPHP\Translations An instance of the Translations class.
     * @throws GroqException
     */
    public static function translations(): \LucianoTonet\GroqPHP\Translations
    {
        return app(GroqPHP::class)->audio()->translations();
    }
    
    /**
     * Get completions instance for generating text completions.
     *
     * @return \LucianoTonet\GroqPHP\Completions An instance of the Completions class.
     * @throws GroqException
     */
    public static function completions(): \LucianoTonet\GroqPHP\Completions
    {
        return app(GroqPHP::class)->chat()->completions();
    }
    
    /**
     * Get reasoning instance for step-by-step reasoning tasks.
     *
     * @return \LucianoTonet\GroqPHP\Reasoning An instance of the Reasoning class.
     * @throws GroqException
     */
    public static function reasoning(): \LucianoTonet\GroqPHP\Reasoning
    {
        return app(GroqPHP::class)->reasoning();
    }
    
    /**
     * Get files instance for managing files.
     *
     * @return \LucianoTonet\GroqPHP\FileManager An instance of the FileManager class.
     * @throws GroqException
     */
    public static function files(): \LucianoTonet\GroqPHP\FileManager
    {
        return app(GroqPHP::class)->files();
    }
}