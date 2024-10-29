<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use Orchestra\Testbench\TestCase;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqPHP\Groq as GroqPHP;

class ConfigTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
    }

    public function testConfigFileIsPublished()
    {
        $this->artisan('vendor:publish', [
            '--provider' => "LucianoTonet\GroqLaravel\GroqServiceProvider",
            '--tag' => 'config'
        ])->assertExitCode(0);

        $this->assertFileExists(config_path('groq.php'));
    }

    public function testConfigValues()
    {
        $this->assertNotNull(config('groq.api_key'));
        $this->assertEquals('https://api.groq.com/openai/v1', config('groq.api_base'));
    }

    public function testSetOptions()
    {
        // Setup
        $initialApiKey = config('groq.api_key');
        
        // Test setting new options
        $newOptions = [
            'apiKey' => 'new_test_key',
            'baseUrl' => 'https://test-api.groq.com/v1',
            'timeout' => 30000,
            'maxRetries' => 3,
            'headers' => ['X-Custom-Header' => 'test'],
            'debug' => true,
            'stream' => true,
            'responseFormat' => 'json'
        ];

        Groq::setOptions($newOptions);
        
        // Verify API key was updated
        $this->assertEquals('new_test_key', Groq::apiKey());
        $this->assertEquals('https://test-api.groq.com/v1', Groq::baseUrl());
        
        // Verify that the instance maintains the new configuration
        $instance1 = app(GroqPHP::class);
        $this->assertEquals('new_test_key', $instance1->apiKey());
        
        // Get a new instance and verify it has the same configuration
        $instance2 = app(GroqPHP::class);
        $this->assertEquals('new_test_key', $instance2->apiKey());
        $this->assertSame($instance1, $instance2); // Should be the same instance
    }

    public function testSetOptionsPartial()
    {
        // Setup
        $initialApiKey = config('groq.api_key');
        
        // Test setting only some options
        $newOptions = [
            'timeout' => 20000,
            'debug' => true
        ];
        
        Groq::setOptions($newOptions);
        
        // Verify API key remained unchanged
        $this->assertEquals($initialApiKey, Groq::apiKey());
    }
}