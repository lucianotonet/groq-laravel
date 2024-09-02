<?php

namespace LucianoTonet\GroqLaravel\Tests\Feature;

use Orchestra\Testbench\TestCase;
use LucianoTonet\GroqLaravel\GroqServiceProvider;

class ConfigTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [GroqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
    }

    public function testConfigFileIsPublished()
    {
        $this->artisan('vendor:publish', [
            '--provider' => "LucianoTonet\GroqLaravel\GroqServiceProvider",
            '--tag' => 'config'
        ])->assertExitCode(0);

        $this->assertFileExists(config_path('groq.php'));
    }

    public function testConfigValues()
    {
        $this->assertNotNull(config('groq.api_key'));
        $this->assertEquals('https://api.groq.com/openai/v1', config('groq.api_base'));
    }
}