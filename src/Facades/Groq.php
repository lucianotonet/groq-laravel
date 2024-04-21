<?php

namespace LucianoTonet\GroqLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use LucianoTonet\GroqPHP\Groq as GroqPHP;

class Groq extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GroqPHP::class;
    }

    protected $apiKey;
    protected $options;

    public function __construct($apiKey = null, $options = [])
    {
        $this->apiKey = $apiKey;
        $this->options = $options;
    }

    public function chat()
    {
        return (new GroqPHP($this->apiKey, $this->options))->chat();
    }
}