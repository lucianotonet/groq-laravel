<?php

namespace Tests\Feature;

use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqLaravel\GroqServiceProvider;
use LucianoTonet\GroqPHP\Reasoning;
use LucianoTonet\GroqPHP\Groq as GroqPHP;
use Orchestra\Testbench\TestCase;

class ReasoningTest extends TestCase
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
    public function it_can_get_reasoning_instance()
    {
        $this->assertInstanceOf(Reasoning::class, Groq::reasoning());
    }

    /** @test */
    public function it_can_analyze_with_raw_format()
    {
        // Create a mock response
        $expectedResponse = [
            'choices' => [
                [
                    'message' => [
                        'content' => "To solve 2+2, I'll add the numbers together.\n2+2 = 4\nThe answer is 4."
                    ]
                ]
            ]
        ];

        // Mock the Reasoning class
        $mockReasoning = $this->createMock(Reasoning::class);
        $mockReasoning->expects($this->once())
            ->method('analyze')
            ->with(
                $this->equalTo('What is 2+2?'),
                $this->callback(function ($options) {
                    return isset($options['model']) && 
                        isset($options['reasoning_format']) && 
                        $options['reasoning_format'] === 'raw';
                })
            )
            ->willReturn($expectedResponse);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('reasoning')
            ->willReturn($mockReasoning);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::reasoning()->analyze('What is 2+2?', [
            'model' => 'llama-3.1-8b-instant',
            'reasoning_format' => 'raw'
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertStringContainsString('The answer is 4', $result['choices'][0]['message']['content']);
    }

    /** @test */
    public function it_can_analyze_with_parsed_format()
    {
        // Create a mock response
        $expectedResponse = [
            'choices' => [
                [
                    'message' => [
                        'content' => 'Paris',
                        'reasoning' => 'The capital of France is Paris. It has been the capital city since 987 CE.'
                    ]
                ]
            ]
        ];

        // Mock the Reasoning class
        $mockReasoning = $this->createMock(Reasoning::class);
        $mockReasoning->expects($this->once())
            ->method('analyze')
            ->with(
                $this->equalTo('What is the capital of France?'),
                $this->callback(function ($options) {
                    return isset($options['model']) && 
                        isset($options['reasoning_format']) && 
                        $options['reasoning_format'] === 'parsed';
                })
            )
            ->willReturn($expectedResponse);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('reasoning')
            ->willReturn($mockReasoning);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::reasoning()->analyze('What is the capital of France?', [
            'model' => 'llama-3.1-70b-instant',
            'reasoning_format' => 'parsed'
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertArrayHasKey('reasoning', $result['choices'][0]['message']);
        $this->assertEquals('Paris', $result['choices'][0]['message']['content']);
    }

    /** @test */
    public function it_can_analyze_with_hidden_format()
    {
        // Create a mock response
        $expectedResponse = [
            'choices' => [
                [
                    'message' => [
                        'content' => 'x = 3 or x = -3'
                    ]
                ]
            ]
        ];

        // Mock the Reasoning class
        $mockReasoning = $this->createMock(Reasoning::class);
        $mockReasoning->expects($this->once())
            ->method('analyze')
            ->with(
                $this->equalTo('Solve x^2 = 9'),
                $this->callback(function ($options) {
                    return isset($options['model']) && 
                        isset($options['reasoning_format']) && 
                        $options['reasoning_format'] === 'hidden';
                })
            )
            ->willReturn($expectedResponse);

        // Mock the Groq class
        $mockGroq = $this->getMockBuilder(GroqPHP::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockGroq->expects($this->once())
            ->method('reasoning')
            ->willReturn($mockReasoning);

        // Replace the bound instance
        $this->app->instance(GroqPHP::class, $mockGroq);
        
        // Test the facade (which now uses our mock)
        $result = Groq::reasoning()->analyze('Solve x^2 = 9', [
            'model' => 'llama-3.1-8b-instant',
            'reasoning_format' => 'hidden'
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('choices', $result);
        $this->assertStringContainsString('x = 3 or x = -3', $result['choices'][0]['message']['content']);
        $this->assertArrayNotHasKey('reasoning', $result['choices'][0]['message']);
    }
} 