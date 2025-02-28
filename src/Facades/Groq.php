<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use LucianoTonet\GroqPHP\GroqException;
use Groq\Resources\Chat\ChatCompletionResponse;
use Groq\Resources\Completions\CompletionResponse;
use Groq\Resources\Models\ModelsResponse;

/**
 * @method static \LucianoTonet\GroqPHP\Chat chat()
 * @method static \LucianoTonet\GroqPHP\Models models()
 * @method static \LucianoTonet\GroqPHP\Vision vision()
 * @method static \LucianoTonet\GroqPHP\Audio audio()
 * @method static \LucianoTonet\GroqPHP\Files files()
 * @method static \LucianoTonet\GroqPHP\Batches batches()
 * @method static void setConfig(array $options)
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
                $instance->getOptions()['apiKey'],
                $options
            ));
            return;
        }
        
        // If no apiKey or baseUrl, just update the existing options
        $instance->setOptions($options);
    }

    /**
     * Get configuration options from the Groq instance.
     *
     * @return array The current configuration options
     * @throws GroqException
     */
    public static function getOptions(): array
    {
        return app(GroqPHP::class)->getOptions();
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
}