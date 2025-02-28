# Groq Laravel

![Groq Laravel](./art.png)

[![Latest Stable Version](https://poser.pugx.org/lucianotonet/groq-laravel/v)](https://packagist.org/packages/lucianotonet/groq-laravel) 
[![Total Downloads](https://poser.pugx.org/lucianotonet/groq-laravel/downloads)](https://packagist.org/packages/lucianotonet/groq-laravel) 
[![License](https://poser.pugx.org/lucianotonet/groq-laravel/license)](https://packagist.org/packages/lucianotonet/groq-laravel)

A Laravel package to easily integrate your application with the Groq API, providing access to popular models like Llama3, Mixtral, and others.

## Installation

1. Install via Composer:
```bash
composer require lucianotonet/groq-laravel
```

2. Publish the configuration file:
```bash
php artisan vendor:publish --provider="LucianoTonet\GroqLaravel\GroqServiceProvider"
```

3. Add your API key to the `.env` file:
```env
GROQ_API_KEY=your-api-key-here
GROQ_MODEL=llama3-8b-8192  # optional, default model
```

## Basic Usage

### Chat

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

$response = Groq::chat()->create([
    'messages' => [
        ['role' => 'user', 'content' => 'Hello, how are you?']
    ]
]);

echo $response['choices'][0]['message']['content'];
```

### Available Models

```php
$models = Groq::models()->list();

foreach ($models['data'] as $model) {
    echo $model['id'] . "\n";
}
```

### Computer Vision

```php
$response = Groq::vision()->analyze(
    'path/to/image.jpg',
    'Describe this image'
);

echo $response['choices'][0]['message']['content'];
```

### Audio

```php
$response = Groq::audio()->transcribe('path/to/audio.mp3');
echo $response['text'];
```

### Batch Processing

```php
// Upload file
$file = Groq::files()->upload('data.jsonl', 'batch');

// Create batch
$batch = Groq::batches()->create([
    'input_file_id' => $file->id,
    'endpoint' => '/v1/chat/completions'
]);
```

## Configuration

The package can be configured through the `config/groq.php` file. The main options are:

```php
return [
    'api_key' => env('GROQ_API_KEY'),
    'model' => env('GROQ_MODEL', 'llama3-8b-8192'),
    'timeout' => env('GROQ_TIMEOUT', 30),
    
    'options' => [
        'temperature' => 0.7,
        'max_tokens' => 150,
        'top_p' => 1.0,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ],
    
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],
];
```

## Runtime Configuration

You can change settings during runtime:

```php
Groq::setConfig([
    'model' => 'mixtral-8x7b',
    'temperature' => 0.9,
    'max_tokens' => 500
]);
```

## Error Handling

The package throws `GroqException` when something goes wrong:

```php
use LucianoTonet\GroqPHP\GroqException;

try {
    $response = Groq::chat()->create([
        'messages' => [
            ['role' => 'user', 'content' => 'Hello!']
        ]
    ]);
} catch (GroqException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Development

### Development Installation

1. Clone the repository
```bash
git clone https://github.com/lucianotonetto/groq-laravel.git
cd groq-laravel
```

2. Install dependencies
```bash
composer install
```

### Running Tests

The package includes unit and integration tests. To run them:

1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Configure your Groq API key in the `.env` file (required only for integration tests):
```env
GROQ_API_KEY=your-api-key-here
```

3. Run the tests:

- All tests:
```bash
composer test
```

- Unit tests only:
```bash
vendor/bin/phpunit tests/Feature/GroqTest.php
```

- Integration tests only:
```bash
vendor/bin/phpunit tests/Feature/GroqIntegrationTest.php
```

- Tests with code coverage:
```bash
vendor/bin/phpunit --coverage-html coverage
```

**Note**: Unit tests don't require an API key. Integration tests will be skipped if no API key is provided.

## Credits

- [Luciano Tonet](https://github.com/lucianotonet)
- [All Contributors](../../contributors)

## License

This package is open-source software licensed under the [MIT license](LICENSE).
