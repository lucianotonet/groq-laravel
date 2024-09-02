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

    'options' => [
        'model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'), // Modelo LLM padrão
        'max_tokens' => env('GROQ_MAX_TOKENS', 150), // Número máximo de tokens
        'stop_sequence' => env('GROQ_STOP_SEQUENCE', null), // Sequência de parada
        'temperature' => env('GROQ_TEMPERATURE', 0.7), // Controle de aleatoriedade
        'top_p' => env('GROQ_TOP_P', 1.0), // Amostragem de núcleo
        'frequency_penalty' => env('GROQ_FREQUENCY_PENALTY', 0), // Penalidade de frequência
        'presence_penalty' => env('GROQ_PRESENCE_PENALTY', 0), // Penalidade de presença
        'n' => env('GROQ_N', 1), // Número de respostas a retornar
    ],

    'rate_limit' => env('GROQ_RATE_LIMIT', 60),
];
