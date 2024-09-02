# Groq Laravel

Groq Laravel is a powerful package that provides seamless integration between Laravel applications and the Groq API, enabling you to leverage the capabilities of language models (LLMs) like LLaMa directly within your PHP projects.

## Features

- **Simple and Intuitive Interface:** Interact with the Groq API using the `Groq` facade, simplifying access to chat, audio, and model functionalities.
- **Robust Error Handling:** Efficiently handle communication errors and Groq API responses by capturing specific exceptions and providing informative messages.
- **Flexible Configuration:** Define multiple Groq API instances, customize request timeouts, configure cache options, and adjust the package behavior to your needs.
- **Detailed Practical Examples:** Explore code examples that demonstrate how to use the Groq Laravel package in real scenarios, including chatbots, audio transcription, and more.
- **Comprehensive Testing:** Ensure the package's quality and reliability with a suite of tests covering integration, unit testing, and configuration aspects.

## Installation

1. Install the package via Composer:

```bash
composer require lucianotonet/groq-laravel
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --provider="LucianoTonet\GroqLaravel\GroqServiceProvider"
```

3. Configure your Groq API credentials in the `.env` file:

```
GROQ_API_KEY=your_api_key_here
GROQ_API_BASE=https://api.groq.com/openai/v1
```

4. (Optional) Configure caching by defining the following environment variables in the `.env` file:

```
GROQ_CACHE_DRIVER=file
GROQ_CACHE_TTL=3600
```

5. Import the `Groq` facade in your classes:

```php
use LucianoTonet\GroqLaravel\Facades\Groq;
```

## Usage

Here's a simple example of creating a chat completion:

```php
$response = Groq::chat()->completion()->create([
    'model' => 'llama-3.1-8b-instant',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello, how are you?'],
    ],
]);
```

Refer to the [documentation](docs/index.md) for more detailed information on available methods, configuration options, and practical examples.

## Contributing

Contributions are welcome! Please follow the guidelines outlined in the [CONTRIBUTING.md](CONTRIBUTING.md) file.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
