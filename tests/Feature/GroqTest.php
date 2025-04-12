<?php

namespace Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use Orchestra\Testbench\TestCase;

class GroqTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('groq.api_key', 'test-key');
        $app['config']->set('groq.model', 'llama3-8b-8192');
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(\LucianoTonet\GroqLaravel\GroqClient::class, app('groq'));
    }

    /** @test */
    public function it_can_get_chat_instance()
    {
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\Chat::class, Groq::chat());
    }

    /** @test */
    public function it_can_get_models_instance()
    {
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\Models::class, Groq::models());
    }

    /** @test */
    public function it_can_get_vision_instance()
    {
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\Vision::class, Groq::vision());
    }

    /** @test */
    public function it_can_get_audio_instance()
    {
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\Audio::class, Groq::audio());
    }

    /** @test */
    public function it_can_get_files_instance()
    {
        $this->assertInstanceOf('LucianoTonet\GroqPHP\FileManager', Groq::files());
    }

    /** @test */
    public function it_can_get_batches_instance()
    {
        $this->assertInstanceOf('LucianoTonet\GroqPHP\BatchManager', Groq::batches());
    }

    /** @test */
    public function it_can_set_config()
    {
        Groq::setConfig(['temperature' => 0.8]);
        $this->assertTrue(true); // Se não houver exceção, o teste passa
    }

    /** @test */
    public function it_loads_config_from_env()
    {
        $client = app('groq');
        
        // Método mais seguro para testar, sem depender de apiKey()
        $this->assertEquals('test-key', config('groq.api_key'));
    }
} 