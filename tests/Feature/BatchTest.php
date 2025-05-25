<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\Tests\TestCase;

class BatchTest extends TestCase
{
    protected $tempFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create temporary batch file with .jsonl extension
        $tempDir = sys_get_temp_dir();
        $this->tempFile = $tempDir . '/groq_batch_' . uniqid() . '.jsonl';
        
        // Create valid JSONL content with required fields
        $content = [
            [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => 'Hello!']
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
        
        file_put_contents($this->tempFile, $jsonlContent);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_batch_instance()
    {
        $batch = Groq::batch();
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\BatchManager::class, $batch);
    }

    /** @test */
    public function it_can_create_batch_with_config()
    {
        config([
            'groq.batch.completion_window' => '5m',
            'groq.batch.max_batch_size' => 20,
            'groq.batch.auto_process' => true,
        ]);

        $batch = Groq::batch();
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\BatchManager::class, $batch);
    }

    /** @test */
    public function it_can_create_batch_with_file()
    {
        // Upload the file first
        $file = Groq::files()->upload($this->tempFile, 'batch');
        $this->assertNotEmpty($file->id);

        try {
            // Create batch with file
            $batch = Groq::batch()->create([
                'input_file_id' => $file->id,
                'endpoint' => '/v1/chat/completions',
                'completion_window' => '24h'
            ]);

            $this->assertInstanceOf(\LucianoTonet\GroqPHP\BatchManager::class, $batch);
        } finally {
            // Cleanup
            Groq::files()->delete($file->id);
        }
    }
} 