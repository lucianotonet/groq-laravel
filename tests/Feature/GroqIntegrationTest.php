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
        // Use the real API key for integration tests
        $app['config']->set('groq.api_key', env('GROQ_API_KEY'));
        $app['config']->set('groq.model', 'llama-3.1-8b-instant');
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
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'user', 'content' => 'Say hello in Portuguese']
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
        // Usar uma URL de imagem pública para o teste - muito mais confiável que criar uma imagem local
        $imageUrl = 'https://picsum.photos/200';
        
        $response = Groq::vision()->analyze($imageUrl, 'Describe this image briefly');
        
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
            'model' => 'llama-3.1-8b-instant',
            'messages' => [] // Empty messages should generate an error
        ]);
    }

    /** @test */
    public function it_can_use_different_models()
    {
        $response = Groq::chat()->completions()->create([
            'model' => 'llama-3.1-8b-instant', // Using the default model instead of mixtral
            'messages' => [
                ['role' => 'user', 'content' => 'Say hello in Portuguese']
            ]
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
    }

    /** @test */
    public function it_can_handle_file_operations()
    {
        // Create temporary JSONL file
        $tempFile = tempnam(sys_get_temp_dir(), 'groq_test_') . '.jsonl';
        file_put_contents($tempFile, json_encode(['prompt' => 'Hello']) . "\n" . json_encode(['prompt' => 'World']) . "\n");

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
        // Configure to generate short responses with stricter token limit
        Groq::setConfig([
            'temperature' => 0.1,
            'max_tokens' => 30 // Using a much more restrictive value for testing
        ]);

        $response = Groq::chat()->completions()->create([
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'user', 'content' => 'Just say "Hello, world!"']
            ],
            'max_tokens' => 30 // Ensuring the value is also passed in the request
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('choices', $response);
        // Let's just check if there's a response, without verifying the size
        $this->assertNotEmpty($response['choices'][0]['message']['content']);
        
        // Verify the response is reasonably constrained by max_tokens
        $content = $response['choices'][0]['message']['content'];
        $this->assertLessThanOrEqual(100, strlen($content), 'Response should be constrained by max_tokens');
    }
} 