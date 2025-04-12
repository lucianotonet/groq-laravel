<?php

namespace Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqPHP\Translations;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use Orchestra\Testbench\TestCase;

class TranslationsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('groq.api_key', 'test-key');
        $app['config']->set('groq.model', 'whisper-large-v3');
    }

    /** @test */
    public function it_can_get_translations_instance()
    {
        $this->assertInstanceOf(Translations::class, Groq::translations());
    }

    /** @test */
    public function it_can_create_translation_with_file()
    {
        // Create a mock response
        $expectedResponse = [
            'text' => 'This is a test translation from Norwegian.'
        ];

        // Mock the Translations class
        $mockTranslations = $this->createMock(Translations::class);
        $mockTranslations->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['file']) && 
                       isset($params['model']) && 
                       $params['model'] === 'whisper-large-v3';
            }))
            ->willReturn($expectedResponse);

        // Mock the Audio class that will return our mock Translations
        $mockAudio = $this->getMockBuilder(\LucianoTonet\GroqPHP\Audio::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockAudio->expects($this->once())
            ->method('translations')
            ->willReturn($mockTranslations);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('audio')
            ->willReturn($mockAudio);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::translations()->create([
            'file' => __DIR__ . '/test-audio.mp3', // This file doesn't need to exist for the mock test
            'model' => 'whisper-large-v3'
        ]);
        
        $this->assertSame($expectedResponse, $result);
        $this->assertEquals('This is a test translation from Norwegian.', $result['text']);
    }

    /** @test */
    public function it_can_create_translation_with_options()
    {
        // Create a mock response with detailed formatting
        $expectedResponse = [
            'text' => 'This is a test translation with special formatting.',
            'segments' => [
                [
                    'id' => 0,
                    'start' => 0.0,
                    'end' => 3.5,
                    'text' => 'This is a test translation'
                ],
                [
                    'id' => 1,
                    'start' => 3.5,
                    'end' => 5.2,
                    'text' => 'with special formatting.'
                ]
            ]
        ];

        // Mock the Translations class
        $mockTranslations = $this->createMock(Translations::class);
        $mockTranslations->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['file']) && 
                       isset($params['temperature']) && 
                       $params['temperature'] === 0.3 &&
                       isset($params['response_format']) && 
                       $params['response_format'] === 'verbose_json';
            }))
            ->willReturn($expectedResponse);

        // Mock the Audio class that will return our mock Translations
        $mockAudio = $this->getMockBuilder(\LucianoTonet\GroqPHP\Audio::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockAudio->expects($this->once())
            ->method('translations')
            ->willReturn($mockTranslations);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('audio')
            ->willReturn($mockAudio);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::translations()->create([
            'file' => __DIR__ . '/test-audio.mp3', // This file doesn't need to exist for the mock test
            'model' => 'whisper-large-v3',
            'temperature' => 0.3,
            'response_format' => 'verbose_json'
        ]);
        
        $this->assertSame($expectedResponse, $result);
        $this->assertEquals('This is a test translation with special formatting.', $result['text']);
        $this->assertCount(2, $result['segments']);
        $this->assertEquals('This is a test translation', $result['segments'][0]['text']);
    }
} 