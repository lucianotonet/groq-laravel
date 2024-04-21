# Groq Laravel package

Laravel package to provide access to the [Groq REST API](https://console.groq.com/docs) using the [Groq-PHP library](https://github.com/lucianotonet/groq-php).


## Installation

You can install the package via composer:

```bash
composer require lucianotonet/groq-laravel
```

### Set up your keys

Set your [Groq API key](https://console.groq.com/keys) on the `.env` file:

```.env
GROQ_API_KEY=gsk_...
```

## Usage

### Groq Facade

You can use the `Groq` facade to interact with the Groq API like this:

```php
use Illuminate\Support\Facades\Route;
use LucianoTonet\GroqLaravel\Facades\Groq;

Route::get('/', function () {
    $groq = new Groq();

    $chatCompletion = $groq->chat()->completions()->create([
        'model' => 'llama3-8b-8192', // llama3-8b-8192, llama3-70b-8192, llama2-70b-4096, mixtral-8x7b-32768, gemma-7b-it
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Explain the importance of low latency LLMs'
            ]
        ],
    ]);

    return $chatCompletion['choices'][0]['message']['content'];
});
```

*Groq Laravel Package* is just a wrapper to the [Grok PHP library](https://github.com/lucianotonet/groq-php), so you can use all the methods and classes from that library through the facade.

All examples found on the [examples dir](https://github.com/lucianotonet/groq-php/examples) can be used with the Groq facade.

## License

This package is open-sourced software licensed under the MIT license. See the [LICENSE](LICENSE) file for more information.
