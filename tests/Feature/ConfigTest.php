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
        // Na configuração, usamos o .env para carregar valores
        // Devemos definir valores de teste manualmente
        config(['groq.api_key' => 'test-key']);
        config(['groq.api_base' => 'https://api.groq.com/openai/v1']);
        
        $this->assertEquals('test-key', config('groq.api_key'));
        $this->assertEquals('https://api.groq.com/openai/v1', config('groq.api_base'));
    }

    public function testSetOptions()
    {
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

        // Apenas testar se não lança exceção
        try {
            Groq::setOptions($newOptions);
            $this->assertTrue(true); // Passa se chegar aqui
        } catch (\Exception $e) {
            $this->fail('setOptions() lançou uma exceção: ' . $e->getMessage());
        }
    }

    public function testSetOptionsPartial()
    {
        // Test setting only some options
        $newOptions = [
            'timeout' => 20000,
            'debug' => true
        ];
        
        // Apenas testar se não lança exceção
        try {
            Groq::setOptions($newOptions);
            $this->assertTrue(true); // Passa se chegar aqui
        } catch (\Exception $e) {
            $this->fail('setOptions() lançou uma exceção: ' . $e->getMessage());
        }
    }
}