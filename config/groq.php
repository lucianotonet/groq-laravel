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
    /*
    |--------------------------------------------------------------------------
    | Groq API Key
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar sua chave de API Groq. Ela será usada quando
    | o GroqClient for criado. Esta chave pode ser obtida no site da Groq.
    |
    */
    'api_key' => env('GROQ_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Modelo Padrão
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar o modelo padrão a ser usado nas solicitações 
    | da API Groq. Esse modelo será usado se nenhum for especificado na requisição.
    |
    */
    'model' => env('GROQ_MODEL', 'llama3-8b-8192'),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configuração para cache das respostas da API. Útil para reduzir o número
    | de requisições e melhorar a performance.
    |
    */
    'cache' => [
        'enabled' => env('GROQ_CACHE_ENABLED', true),
        'ttl' => env('GROQ_CACHE_TTL', 3600), // 1 hora em segundos
        'key_prefix' => env('GROQ_CACHE_KEY_PREFIX', 'groq_'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Define o tempo máximo em segundos para aguardar uma resposta da API.
    |
    */
    'timeout' => env('GROQ_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | URL base da API Groq. Normalmente você não precisa mudar isso,
    | mas está disponível caso a Groq mude seu endpoint no futuro.
    |
    */
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

    'vision' => [
        'model' => env('GROQ_VISION_MODEL', 'llava-v1.5-7b-4096-preview'),
        'max_tokens' => env('GROQ_VISION_MAX_TOKENS', 300),
    ],
];
