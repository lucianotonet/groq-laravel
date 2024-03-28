<?php

namespace LucianoTonet\GroqLaravel;
use LucianoTonet\GroqPHP\Chat;

class GroqLaravel extends \LucianoTonet\GroqPHP\Groq
{
    public function chat(): Chat
    {
        return new Chat($this);
    }
}
