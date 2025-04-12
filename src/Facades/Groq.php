<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use LucianoTonet\GroqPHP\GroqException;

/**
 * Class Groq
 * This class serves as a facade for the GroqPHP library, providing a simplified interface
 * for accessing Groq API methods within a Laravel application.
 */
class Groq extends Facade
{
    /**
     * Retrieve the class name of the GroqPHP instance.
     *
     * @return string The class name of the GroqPHP instance.
     */
    protected static function getFacadeAccessor(): string
    {
        return GroqPHP::class;
    }

    /**
     * Initiate a chat session with the Groq API.
     *
     * @return \LucianoTonet\GroqPHP\Chat An instance of the Chat class for managing chat sessions.
     * @throws GroqException
     */
    public static function chat(): \LucianoTonet\GroqPHP\Chat
    {
        return app(GroqPHP::class)->chat();
    }

    /**
     * Initiate an audio session with the Groq API.
     *
     * @return \LucianoTonet\GroqPHP\Audio An instance of the Audio class for managing audio sessions.
     * @throws GroqException
     */
    public static function audio(): \LucianoTonet\GroqPHP\Audio
    {
        return app(GroqPHP::class)->audio();
    }

    /**
     * Retrieve the list of available models from the Groq API.
     *
     * @return \LucianoTonet\GroqPHP\Models An instance of the Models class containing available models.
     * @throws GroqException
     */
    public static function models(): \LucianoTonet\GroqPHP\Models
    {
        return app(GroqPHP::class)->models();
    }

    /**
     * Set configuration options for the Groq instance.
     *
     * @param array $options An associative array of options to configure the Groq instance.
     * @return void
     * @throws GroqException
     */
    public static function setOptions(array $options): void
    {
        $instance = app(GroqPHP::class);
        
        // If apiKey is present in options, update the instance
        if (isset($options['apiKey'])) {
            app()->forgetInstance(GroqPHP::class);
            app()->instance(GroqPHP::class, new GroqPHP(
                $options['apiKey'],
                array_merge(
                    ['baseUrl' => config('groq.api_base', 'https://api.groq.com/openai/v1')],
                    $options
                )
            ));
            return;
        }

        // If baseUrl is present in options, update the instance
        if (isset($options['baseUrl'])) {
            app()->forgetInstance(GroqPHP::class);
            app()->instance(GroqPHP::class, new GroqPHP(
                $instance->apiKey(),
                $options
            ));
            return;
        }
        
        // If no apiKey or baseUrl, just update the existing options
        $instance->setOptions($options);
    }

    /**
     * Retrieve the base URL for the Groq API.
     *
     * @return string The base URL used for API requests.
     * @throws GroqException
     */
    public static function baseUrl(): string
    {
        return app(GroqPHP::class)->baseUrl();
    }

    /**
     * Retrieve the API key used for authentication with the Groq API.
     *
     * @return string The API key for authenticating requests.
     * @throws GroqException
     */
    public static function apiKey(): string
    {
        return app(GroqPHP::class)->apiKey();
    }

    /**
     * Iniciar uma sessão de visão com a API Groq.
     *
     * @return \LucianoTonet\GroqPHP\Vision Uma instância da classe Vision para gerenciar sessões de visão.
     * @throws GroqException
     */
    public static function vision(): \LucianoTonet\GroqPHP\Vision
    {
        return app(GroqPHP::class)->vision();
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