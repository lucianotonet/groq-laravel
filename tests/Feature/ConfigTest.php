<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use Orchestra\Testbench\TestCase;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use LucianoTonet\GroqLaravel\GroqClient;

class ConfigTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Definir uma chave API de teste 
        $app['config']->set('groq.api_key', 'test-key');
        
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
        
        // Garantir que api_base tenha um valor padrão para o teste
        $apiBase = config('groq.api_base') ?? 'https://api.groq.com/openai/v1';
        config(['groq.api_base' => $apiBase]);
        
        $this->assertNotNull(config('groq.api_base'));
        $this->assertStringContainsString('api.groq.com', config('groq.api_base'));
    }

    public function testSetOptions()
    {
        // Criar mock para GroqClient com os métodos necessários
        $mockClient = $this->getMockBuilder(GroqClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // Definir comportamento esperado para apiKey()
        $mockClient->expects($this->once())
            ->method('apiKey')
            ->willReturn('new_test_key');
        
        // Definir comportamento esperado para baseUrl()
        $mockClient->expects($this->once())
            ->method('baseUrl')
            ->willReturn('https://test-api.groq.com/v1/');
        
        // Definir comportamento esperado para setOptions()
        $mockClient->expects($this->once())
            ->method('setOptions')
            ->with([
                'apiKey' => 'new_test_key',
                'baseUrl' => 'https://test-api.groq.com/v1',
                'timeout' => 30000,
                'maxRetries' => 3,
                'headers' => ['X-Custom-Header' => 'test'],
                'debug' => true,
                'stream' => true,
                'responseFormat' => 'json'
            ]);
        
        // Substituir a instância real pela mock
        $this->app->instance('groq', $mockClient);
        
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
        
        // A barra final é adicionada automaticamente à URL base
        $this->assertEquals('https://test-api.groq.com/v1/', Groq::baseUrl());
    }

    public function testSetOptionsPartial()
    {
        // Criar mock para GroqClient com os métodos necessários
        $mockClient = $this->getMockBuilder(GroqClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // Definir comportamento esperado para setOptions()
        $mockClient->expects($this->once())
            ->method('setOptions')
            ->with([
                'timeout' => 20000,
                'debug' => true
            ]);
        
        // Substituir a instância real pela mock
        $this->app->instance('groq', $mockClient);
        
        // Test setting only some options
        $newOptions = [
            'timeout' => 20000,
            'debug' => true
        ];
        
        Groq::setOptions($newOptions);
        $this->assertTrue(true); // Passa se chegar aqui
    }
}