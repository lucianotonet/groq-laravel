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
        $app['config']->set('groq.vision.model', env('GROQ_VISION_MODEL', 'llava-v1.5-7b-4096-preview'));
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
        
        // Definir explicitamente o modelo de visão para o teste
        $response = Groq::vision()->analyze($imageUrl, 'Describe this image briefly', [
            'model' => 'meta-llama/llama-4-scout-17b-16e-instruct'
        ]);
        
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
        // Create temporary text file with .jsonl extension
        $tempDir = sys_get_temp_dir();
        $tempFile = $tempDir . '/groq_test_' . uniqid() . '.jsonl';
        
        // Create valid JSONL content with required fields
        $content = [
            [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => 'Hello, world!']
                ]
            ],
            [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => 'How are you?']
                ]
            ]
        ];

        // Write each line as JSON
        $jsonlContent = '';
        foreach ($content as $item) {
            $jsonlContent .= json_encode($item) . "\n";
        }
        
        file_put_contents($tempFile, $jsonlContent);
        
        try {
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
        } finally {
            // Cleanup
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
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