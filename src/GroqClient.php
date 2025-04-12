<?php

namespace LucianoTonet\GroqLaravel;

use LucianoTonet\GroqPHP\Groq;
use LucianoTonet\GroqPHP\GroqException;

class GroqClient
{
    /**
     * The Groq PHP SDK client instance.
     *
     * @var \LucianoTonet\GroqPHP\Groq
     */
    protected Groq $client;

    /**
     * Create a new GroqClient instance.
     * 
     * Priority for API key:
     * 1. Constructor parameter
     * 2. Environment variable (GROQ_API_KEY)
     * 3. Config file (config/groq.php)
     *
     * @param string|null $apiKey Optional API key to override the one in config
     * @throws \LucianoTonet\GroqPHP\GroqException
     */
    public function __construct(?string $apiKey = null)
    {
        // Try to get API key from different sources in order of priority
        $apiKey = $apiKey 
            ?? env('GROQ_API_KEY') 
            ?? config('groq.api_key');

        // Validate API key
        if (empty($apiKey)) {
            throw new GroqException(
                'No API key found. Please provide it via:' . PHP_EOL .
                '1. Constructor parameter' . PHP_EOL .
                '2. Environment variable (GROQ_API_KEY)' . PHP_EOL .
                '3. Config file (config/groq.php)',
                400, // HTTP 400 Bad Request code
                'invalid_request_error' // Error type
            );
        }
        
        // Initialize client with API key and default config
        $this->client = new Groq($apiKey, $this->getDefaultConfig());
    }

    /**
     * Get the default configuration from Laravel config.
     *
     * @return array
     */
    protected function getDefaultConfig(): array
    {
        return [
            'baseUrl' => config('groq.api_base', 'https://api.groq.com/openai/v1'),
            'timeout' => config('groq.timeout', 30),
            'model' => config('groq.model', 'llama-3.1-8b-instant'),
            'max_tokens' => config('groq.options.max_tokens', 150),
            'temperature' => config('groq.options.temperature', 0.7),
            'top_p' => config('groq.options.top_p', 1.0),
            'frequency_penalty' => config('groq.options.frequency_penalty', 0),
            'presence_penalty' => config('groq.options.presence_penalty', 0),
        ];
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
     * @return \LucianoTonet\GroqPHP\FileManager
     */
    public function files()
    {
        return $this->client->files();
    }

    /**
     * Get batches instance
     * 
     * @return \LucianoTonet\GroqPHP\BatchManager
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
        // Get current client options if available
        $currentOptions = [];
        if (property_exists($this->client, 'options')) {
            $currentOptions = $this->client->options;
        }
        
        // Merge in order of precedence: 
        // 1. New options (highest priority)
        // 2. Current options (if they exist)
        // 3. Default config (lowest priority)
        $mergedOptions = array_merge(
            $this->getDefaultConfig(), 
            $currentOptions, 
            $options
        );
        
        // Create new client with updated options
        $this->client = new Groq(
            $this->getApiKey(), // Use the getter method instead of direct property access
            $mergedOptions
        );
    }

    /**
     * Set configuration options (alias for setConfig)
     * 
     * @param array $options
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->setConfig($options);
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
    
    /**
     * Get the API key from the client
     * 
     * @return string
     */
    private function getApiKey(): string
    {
        // Access the API key through reflection since it's not directly accessible
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('apiKey');
        $property->setAccessible(true);
        return $property->getValue($this->client);
    }
}