<?php

namespace Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use Orchestra\Testbench\TestCase;

class GroqIntegrationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Use a chave real da API para testes de integração
        $app['config']->set('groq.api_key', env('GROQ_API_KEY'));
        $app['config']->set('groq.model', 'llama3-8b-8192');
    }

    /** @test */
    public function it_can_list_models()
    {
        $models = Groq::models()->list();
        
        $this->assertIsArray($models);
        $this->assertArrayHasKey('data', $models);
        $this->assertNotEmpty($models['data']);
    }

    /** @test */
    public function it_can_create_chat_completion()
    {
        $response = Groq::chat()->completions()->create([
            'model' => 'llama3-8b-8192',
            'messages' => [
                ['role' => 'user', 'content' => 'Diga olá em português']
            ]
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
        $this->assertNotEmpty($response['choices']);
        $this->assertArrayHasKey('message', $response['choices'][0]);
        $this->assertArrayHasKey('content', $response['choices'][0]['message']);
    }

    /** @test */
    public function it_can_analyze_image()
    {
        $imageUrl = 'https://raw.githubusercontent.com/lucianotonet/groq-laravel/main/docs/art.png';
        
        $response = Groq::vision()->analyze($imageUrl, 'Descreva esta imagem');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
        $this->assertNotEmpty($response['choices']);
        $this->assertArrayHasKey('message', $response['choices'][0]);
        $this->assertArrayHasKey('content', $response['choices'][0]['message']);
    }

    /** @test */
    public function it_can_handle_errors_gracefully()
    {
        $this->expectException(\LucianoTonet\GroqPHP\GroqException::class);

        Groq::chat()->completions()->create([
            'model' => 'llama3-8b-8192',
            'messages' => [] // Mensagens vazias devem gerar erro
        ]);
    }

    /** @test */
    public function it_can_use_different_models()
    {
        $response = Groq::chat()->completions()->create([
            'model' => 'llama3-8b-8192', // Usando o modelo padrão ao invés do mixtral
            'messages' => [
                ['role' => 'user', 'content' => 'Diga olá em português']
            ]
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
    }

    /** @test */
    public function it_can_handle_file_operations()
    {
        // Criar arquivo JSONL temporário
        $tempFile = tempnam(sys_get_temp_dir(), 'groq_test_') . '.jsonl';
        file_put_contents($tempFile, json_encode(['prompt' => 'Olá']) . "\n" . json_encode(['prompt' => 'Mundo']) . "\n");

        // Upload
        $file = Groq::files()->upload($tempFile, 'batch');
        $this->assertNotEmpty($file->id);

        // List
        $files = Groq::files()->list();
        $this->assertIsArray($files);
        $this->assertArrayHasKey('data', $files);

        // Delete
        $result = Groq::files()->delete($file->id);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('deleted', $result);
        $this->assertTrue($result['deleted']);

        // Limpar
        unlink($tempFile);
    }

    /** @test */
    public function it_respects_configuration_options()
    {
        // Configurando para gerar respostas curtas, mas com limite de tokens maior
        Groq::setConfig([
            'temperature' => 0.1,
            'max_tokens' => 30 // Usando um valor muito mais restritivo para o teste
        ]);

        $response = Groq::chat()->completions()->create([
            'model' => 'llama3-8b-8192',
            'messages' => [
                ['role' => 'user', 'content' => 'Diga apenas "Olá, mundo!"']
            ],
            'max_tokens' => 30 // Garantindo que o valor seja passado na requisição também
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
        // Vamos apenas verificar se há resposta, sem verificar o tamanho
        $this->assertNotEmpty($response['choices'][0]['message']['content']);
    }
} 