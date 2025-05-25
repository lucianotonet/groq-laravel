<?php

namespace LucianoTonet\GroqLaravel\Tests;

use LucianoTonet\GroqLaravel\GroqServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Use the API key from .env file
        $apiKey = env('GROQ_API_KEY');
        
        if (empty($apiKey)) {
            $this->markTestSkipped('GROQ_API_KEY not set in .env file');
        }
        
        $this->app['config']->set('groq.api_key', $apiKey);
        $this->app['config']->set('groq.model', 'llama-3.1-8b-instant');
        $this->app['config']->set('groq.api_base', 'https://api.groq.com/openai/v1');
        
        // Batch configuration
        $this->app['config']->set('groq.batch.completion_window', '24h');
        $this->app['config']->set('groq.batch.max_batch_size', 20);
        $this->app['config']->set('groq.batch.auto_process', true);

        // Speech configuration
        $this->app['config']->set('groq.speech.model', 'playai-tts');
        $this->app['config']->set('groq.speech.voice', 'Bryan-PlayAI');
        $this->app['config']->set('groq.speech.response_format', 'wav');
    }

    protected function getPackageProviders($app)
    {
        return [
            GroqServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Configurações adicionais do ambiente de teste, se necessário
    }
} 