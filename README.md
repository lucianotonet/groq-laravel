# Groq Laravel package

Laravel package to provide access to the [Groq REST API](https://console.groq.com/docs) using the [Groq-PHP library](https://github.com/lucianotonet/groq-php).


## Installation

You can install the package via composer:

```bash
composer require lucianotonet/groq-laravel
```

## Usage

### Set up your keys

Set your [Groq API key](https://console.groq.com/keys) on the `.env` file:

```.env
GROQ_API_KEY=gsk_...
```

If you need, you can set an alternative proxy base URL:
```
GROQ_API_BASE_URL=https://api.groq.com/openai/v1 # can be overitten by request
``` 

### Groq Facade

You can use the `Groq` facade to interact with the Groq package:

```php
use Illuminate\Support\Facades\Route;
use LucianoTonet\GroqLaravel\Facades\Groq;

Route::get('/', function () {
    $groq = new Groq();

    $chatCompletion = $groq->chat()->completions()->create([
        'model' => 'llama2-70b-4096', // llama2-70b-4096, mixtral-8x7b-32768, gemma-7b-it
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

## License

This package is open-sourced software licensed under the MIT license. See the [LICENSE](LICENSE) file for more information.

