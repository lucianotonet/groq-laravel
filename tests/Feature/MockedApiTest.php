<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use Orchestra\Testbench\TestCase;
use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use Illuminate\Support\Facades\Storage;

class MockedApiTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Groq' => Groq::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadEnvironmentVariables();
    }

    protected function loadEnvironmentVariables()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
    }

    /** @test */
    public function testMockedApiCall()
    {
        $this->markTestSkipped('This test is currently skipped.');
        
        // Carregar a resposta mockada do arquivo
        $mockResponse = json_decode(Storage::disk('local')->get('mocks/real_api_response.json'), true);

        // Mockar a chamada da API
        Groq::shouldReceive('chat->completions->create')
            ->andReturn($mockResponse);

        $response = Groq::chat()->completions()->create([
            'model' => 'llama-3.1-8b-instant', // Adicionar o modelo
            'messages' => [
                ['role' => 'user', 'content' => 'Hello, how are you?']
            ],
        ]);

        $this->assertEquals($mockResponse, $response);

        // Verificar a resposta mockada
        $this->assertArrayHasKey('choices', $mockResponse);
        $this->assertNotEmpty($mockResponse['choices']);
    }
}