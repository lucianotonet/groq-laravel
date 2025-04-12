<?php

namespace Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqPHP\Completions;
use LucianoTonet\GroqPHP\Stream;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use LucianoTonet\GroqPHP\Chat;
use Orchestra\Testbench\TestCase;

class CompletionsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('groq.api_key', 'test-key');
        $app['config']->set('groq.model', 'llama-3.1-8b-instant');
    }

    /** @test */
    public function it_can_get_completions_instance()
    {
        $this->assertInstanceOf(Completions::class, Groq::completions());
    }

    /** @test */
    public function it_can_create_completion_with_basic_options()
    {
        // Create a mock response
        $expectedResponse = [
            'choices' => [
                [
                    'message' => [
                        'content' => 'Test response'
                    ]
                ]
            ]
        ];

        // Mock the Completions class
        $mockCompletions = $this->createMock(Completions::class);
        $mockCompletions->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['model']) && 
                    isset($params['messages']) && 
                    isset($params['temperature']);
            }))
            ->willReturn($expectedResponse);

        // Mock the Chat class that will return our mock Completions
        $mockChat = $this->getMockBuilder(Chat::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockChat->expects($this->once())
            ->method('completions')
            ->willReturn($mockCompletions);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('chat')
            ->willReturn($mockChat);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::completions()->create([
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello, world!']
            ],
            'temperature' => 0.7
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertEquals('Test response', $result['choices'][0]['message']['content']);
    }

    /** @test */
    public function it_can_handle_image_content()
    {
        // Create a mock response
        $expectedResponse = [
            'choices' => [
                [
                    'message' => [
                        'content' => 'Image description'
                    ]
                ]
            ]
        ];

        // Mock the Completions class
        $mockCompletions = $this->createMock(Completions::class);
        $mockCompletions->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['messages'][0]['content']) && 
                    is_array($params['messages'][0]['content']) &&
                    isset($params['messages'][0]['content'][1]['type']) &&
                    $params['messages'][0]['content'][1]['type'] === 'image_url';
            }))
            ->willReturn($expectedResponse);

        // Mock the Chat class that will return our mock Completions
        $mockChat = $this->getMockBuilder(Chat::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockChat->expects($this->once())
            ->method('completions')
            ->willReturn($mockCompletions);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('chat')
            ->willReturn($mockChat);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::completions()->create([
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => 'What is in this image?'],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'https://example.com/image.jpg'
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertEquals('Image description', $result['choices'][0]['message']['content']);
    }
    
    /** @test */
    public function it_can_stream_completions()
    {
        // Create a mock Stream object
        $mockStream = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // Set up the mock to implement the chunks generator
        $mockStream->expects($this->once())
            ->method('chunks')
            ->willReturn((function() {
                yield ['choices' => [['delta' => ['content' => 'Hello']]]];
                yield ['choices' => [['delta' => ['content' => ' world!']]]];
            })());

        // Mock the Completions class
        $mockCompletions = $this->createMock(Completions::class);
        $mockCompletions->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($params) {
                return isset($params['stream']) && $params['stream'] === true;
            }))
            ->willReturn($mockStream);

        // Mock the Chat class that will return our mock Completions
        $mockChat = $this->getMockBuilder(Chat::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockChat->expects($this->once())
            ->method('completions')
            ->willReturn($mockCompletions);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('chat')
            ->willReturn($mockChat);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the streaming functionality
        $stream = Groq::completions()->create([
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello, world!']
            ],
            'stream' => true
        ]);
        
        $this->assertInstanceOf(Stream::class, $stream);
        
        // Test iterating over the stream using chunks()
        $result = '';
        foreach ($stream->chunks() as $response) {
            $result .= $response['choices'][0]['delta']['content'] ?? '';
        }
        
        $this->assertEquals('Hello world!', $result);
    }
}