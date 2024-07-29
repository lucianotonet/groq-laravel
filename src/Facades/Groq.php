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
        app(GroqPHP::class)->setOptions($options);
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
}