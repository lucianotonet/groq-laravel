---
title: "Configuration"
---

# Configuration

The Groq Laravel package offers various configuration options to customize its operation.

## Configuration File

After publishing the configuration file (`config/groq.php`), you can customize it:

```php
return [
    'api_key' => env('GROQ_API_KEY'),
    'api_base' => env('GROQ_API_BASE', 'https://api.groq.com/openai/v1'),
    'timeout' => env('GROQ_TIMEOUT', 30), // Request timeout in seconds
    'instances' => [
        'default' => [
            'api_key' => env('GROQ_API_KEY'),
            'api_base' => env('GROQ_API_BASE', 'https://api.groq.com/openai/v1'),
        ],
        // ... other instances
    ],
    'cache' => [
        'driver' => env('GROQ_CACHE_DRIVER', 'file'),
        'ttl' => env('GROQ_CACHE_TTL', 3600),
    ],
];
```

## Environment Variables

You can use environment variables in the `.env` file to configure the package:

* `GROQ_API_KEY`: Your Groq API key.
* `GROQ_API_BASE`: Base URL of the Groq API.
* `GROQ_TIMEOUT`: Timeout for API requests in seconds.
* `GROQ_CACHE_DRIVER`: Cache driver (e.g., `file`, `redis`).
* `GROQ_CACHE_TTL`: Cache time-to-live in seconds.

## Multiple Instances

To configure multiple instances of the Groq API, add new entries to the `instances` array in the `config/groq.php` file:

```php
'instances' => [
    'default' => [
        'api_key' => env('GROQ_API_KEY'),
        'api_base' => env('GROQ_API_BASE'),
    ],
    'custom' => [
        'api_key' => env('GROQ_CUSTOM_API_KEY'),
        'api_base' => env('GROQ_CUSTOM_API_BASE'),
    ],
],
```

To use a specific instance, use the `instance()` method of the `Groq` facade:

```php
$response = Groq::instance('custom')->chat()->completions()->create([
    // ...
]);
```

## Cache

The Groq Laravel package offers support for caching API responses. You can configure the driver and cache lifetime in the configuration file or via environment variables.
