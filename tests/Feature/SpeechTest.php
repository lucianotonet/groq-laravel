<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\Tests\TestCase;
use LucianoTonet\GroqPHP\GroqException;

class SpeechTest extends TestCase
{
    /** @test */
    public function it_can_create_speech_instance()
    {
        $speech = Groq::speech();
        $this->assertInstanceOf(\LucianoTonet\GroqPHP\Speech::class, $speech);
    }

    /** @test */
    public function it_throws_exception_when_input_is_missing()
    {
        $this->expectException(GroqException::class);
        $this->expectExceptionMessage('Input text is required');
        
        Groq::speech()->create();
    }

    /** @test */
    public function it_throws_exception_when_voice_is_missing()
    {
        $this->expectException(GroqException::class);
        $this->expectExceptionMessage('Voice is required');
        
        Groq::speech()
            ->input('Hello, world!')
            ->create();
    }

    /** @test */
    public function it_can_set_speech_options()
    {
        $speech = Groq::speech()
            ->model('playai-tts')
            ->input('Hello, world!')
            ->voice('Bryan-PlayAI')
            ->responseFormat('wav');

        $this->assertInstanceOf(\LucianoTonet\GroqPHP\Speech::class, $speech);
    }

    /** @test */
    public function it_uses_correct_default_values()
    {
        $speech = Groq::speech();
        
        // Reflection para acessar propriedades privadas
        $reflection = new \ReflectionClass($speech);
        
        $modelProperty = $reflection->getProperty('model');
        $modelProperty->setAccessible(true);
        $this->assertEquals('playai-tts', $modelProperty->getValue($speech));
        
        $responseFormatProperty = $reflection->getProperty('responseFormat');
        $responseFormatProperty->setAccessible(true);
        $this->assertEquals('wav', $responseFormatProperty->getValue($speech));
    }
} 