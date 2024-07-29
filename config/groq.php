<?php

/**
 * Configuration settings for the GROQ API integration.
 *
 * @return array
 *   An array of GROQ API configuration settings.
 *   - api_key: The API key to authenticate with the GROQ API.
 *   - api_base: The base URL for the GROQ API endpoint.
 *   - options: Additional options to pass to the GROQ API client.
 */
return [
    'api_key' => env('GROQ_API_KEY'),
    'api_base' => env('GROQ_API_BASE', 'https://api.groq.com/openai/v1'),
    'options' => [],
];