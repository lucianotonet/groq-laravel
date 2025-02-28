<?php

namespace LucianoTonet\GroqLaravel;

use LucianoTonet\GroqPHP\Groq;

class GroqClient
{
    protected Groq $client;

    public function __construct(?string $apiKey = null)
    {
        $apiKey = $apiKey ?? config('groq.api_key');
        
        $this->client = new Groq($apiKey, [
            'baseUrl' => config('groq.api_base', 'https://api.groq.com/openai/v1'),
            'timeout' => config('groq.timeout', 30),
            'model' => config('groq.model', 'llama3-8b-8192'),
            'max_tokens' => config('groq.options.max_tokens', 150),
            'temperature' => config('groq.options.temperature', 0.7),
            'top_p' => config('groq.options.top_p', 1.0),
            'frequency_penalty' => config('groq.options.frequency_penalty', 0),
            'presence_penalty' => config('groq.options.presence_penalty', 0),
        ]);
    }

    /**
     * Get chat instance
     * 
     * @return \LucianoTonet\GroqPHP\Chat
     */
    public function chat()
    {
        return $this->client->chat();
    }

    /**
     * Get models instance
     * 
     * @return \LucianoTonet\GroqPHP\Models
     */
    public function models()
    {
        return $this->client->models();
    }

    /**
     * Get vision instance
     * 
     * @return \LucianoTonet\GroqPHP\Vision
     */
    public function vision()
    {
        return $this->client->vision();
    }

    /**
     * Get audio instance
     * 
     * @return \LucianoTonet\GroqPHP\Audio
     */
    public function audio()
    {
        return $this->client->audio();
    }

    /**
     * Get files instance
     * 
     * @return \LucianoTonet\GroqPHP\Files
     */
    public function files()
    {
        return $this->client->files();
    }

    /**
     * Get batches instance
     * 
     * @return \LucianoTonet\GroqPHP\Batches
     */
    public function batches()
    {
        return $this->client->batches();
    }

    /**
     * Set configuration options
     * 
     * @param array $options
     * @return void
     */
    public function setConfig(array $options): void
    {
        $this->client->setOptions($options);
    }

    /**
     * Get configuration options
     * 
     * @return array
     */
    public function getConfig(): array
    {
        return $this->client->getOptions();
    }

    /**
     * Get the underlying Groq client instance
     * 
     * @return Groq
     */
    public function getClient(): Groq
    {
        return $this->client;
    }
} 