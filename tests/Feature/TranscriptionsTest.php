<?php

namespace Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqPHP\Transcriptions;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use Orchestra\Testbench\TestCase;

class TranscriptionsTest extends TestCase
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
    public function it_can_get_transcriptions_instance()
    {
        $this->assertInstanceOf(Transcriptions::class, Groq::transcriptions());
    }

    /** @test */
    public function it_can_create_transcription_with_file()
    {
        // Create a mock response
        $expectedResponse = [
            'text' => 'This is a test transcription.'
        ];

        // Mock the Transcriptions class
        $mockTranscriptions = $this->createMock(Transcriptions::class);
        $mockTranscriptions->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['file']) && 
                       isset($params['model']) && 
                       $params['model'] === 'whisper-large-v3';
            }))
            ->willReturn($expectedResponse);

        // Mock the Audio class that will return our mock Transcriptions
        $mockAudio = $this->getMockBuilder(\LucianoTonet\GroqPHP\Audio::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockAudio->expects($this->once())
            ->method('transcriptions')
            ->willReturn($mockTranscriptions);

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
        $result = Groq::transcriptions()->create([
            'file' => __DIR__ . '/test-audio.mp3', // This file doesn't need to exist for the mock test
            'model' => 'whisper-large-v3'
        ]);
        
        $this->assertSame($expectedResponse, $result);
        $this->assertEquals('This is a test transcription.', $result['text']);
    }

    /** @test */
    public function it_can_create_transcription_with_options()
    {
        // Create a mock response
        $expectedResponse = [
            'text' => 'Dette er en test transkripsjon på norsk.',
            'language' => 'no'
        ];

        // Mock the Transcriptions class
        $mockTranscriptions = $this->createMock(Transcriptions::class);
        $mockTranscriptions->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['file']) && 
                       isset($params['language']) && 
                       $params['language'] === 'no' &&
                       isset($params['temperature']) && 
                       $params['temperature'] === 0.3 &&
                       isset($params['response_format']) && 
                       $params['response_format'] === 'json';
            }))
            ->willReturn($expectedResponse);

        // Mock the Audio class that will return our mock Transcriptions
        $mockAudio = $this->getMockBuilder(\LucianoTonet\GroqPHP\Audio::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockAudio->expects($this->once())
            ->method('transcriptions')
            ->willReturn($mockTranscriptions);

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
        $result = Groq::transcriptions()->create([
            'file' => __DIR__ . '/test-audio.mp3', // This file doesn't need to exist for the mock test
            'model' => 'whisper-large-v3',
            'language' => 'no',
            'temperature' => 0.3,
            'response_format' => 'json'
        ]);
        
        $this->assertSame($expectedResponse, $result);
        $this->assertEquals('Dette er en test transkripsjon på norsk.', $result['text']);
        $this->assertEquals('no', $result['language']);
    }
} 