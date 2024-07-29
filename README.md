# Groq Laravel

The Groq Laravel package provides a service provider and facade to integrate the GroqPHP library into your Laravel application. This allows you to easily interact with the Groq API.

## Installation

Install the package with Composer:

```bash
composer require lucianotonet/groq-laravel
```

## Configuration

After registering the service provider, publish the configuration file:

```bash
php artisan vendor:publish --provider="LucianoTonet\\GroqLaravel\\GroqServiceProvider"
```

This will create a `config/groq.php` file where you can set your Groq API key and base URL:

```php
return [
    'api_key' => env('GROQ_API_KEY'),
    'base_url' => env('GROQ_API_BASE', 'https://api.groq.com/openai/v1'),
];
```

You can also set these values in your `.env` file:

```
GROQ_API_KEY=your-api-key-here
GROQ_API_BASE=https://api.groq.com/openai/v1
```

## Usage

### `Groq` Facade

This package provides a convenient `Groq` facade to interact with the Groq API.

### Examples

To use the Groq facade, first import it into your class:
```php
use LucianoTonet\GroqLaravel\Facades\Groq;
```

#### Chat Completions

```php
// Starting a Chat Session
$response = Groq::chat()->completions()->create([
    'messages' => [
        ['role' => 'user', 'content' => 'Hello, world!'],
    ],
]);

// Process the response
echo $response['choices'][0]['message']['content'];
```

#### Chat Streaming

```php
$message = 'Hello, world!';

// Make the call to the Groq API with streaming enabled
$response = Groq::chat()->completions()->create([
    'model' => 'mixtral-8x7b-32768',
    'messages' => [
        [
            'role' => 'user',
            'content' => $message
        ]
    ],
    'stream' => true
]);

// Process the response chunks
foreach ($response->chunks() as $chunk) {
    if (isset($chunk['choices'][0]['delta']['role'])) {
        echo "<strong>" . $chunk['choices'][0]['delta']['role'] . ":</strong> ";
    }

    if (isset($chunk['choices'][0]['delta']['content'])) {
        echo $chunk['choices'][0]['delta']['content'];
    }
}
```

#### Tool Usage Example

```php
// Define the tools to be used
$tools = [
    'search' => function ($args) {
        // Implement search tool logic
        return 'Search results for ' . $args['query'];
    },
    'calculator' => function ($args) {
        // Implement calculator tool logic
        return $args['a'] + $args['b'];
    },
];

$messages = [
    ['role' => 'user', 'content' => 'Calculate 3 + 5 using the calculator tool.']
];

// Make the call to the Groq API
$response = Groq::chat()->completions()->create([
    'model' => 'llama3-groq-70b-8192-tool-use-preview',
    'messages' => $messages,
    'temperature' => 0,
    'tool_choice' => 'auto',
    'tools' => $tools
]);

// Process the API response
if (!empty($response['choices'][0]['message']['tool_calls'])) {
    foreach ($response['choices'][0]['message']['tool_calls'] as $tool_call) {
        if ($tool_call['function']['name']) {
            $function_args = json_decode($tool_call['function']['arguments'], true);

            // Call the function defined earlier
            $function_response = $tool_call['function']['name']($function_args);

            // Add the function response to the message
            $messages[] = [
                'tool_call_id' => $tool_call['id'],
                'role' => 'tool',
                'name' => $tool_call['function']['name'],
                'content' => $function_response,
            ];
        }
    }

    // Make a new call to the Groq API with the function response
    $response = Groq::chat()->completions()->create([
        'model' => 'llama3-groq-70b-8192-tool-use-preview',
        'messages' => $messages
    ]);
}

// Display the final response
echo $response['choices'][0]['message']['content'];
```

#### Error Handling

The Groq-Laravel package throws a `GroqException` for any errors encountered while communicating with the Groq API. You can catch this exception:

```php
try {
    $response = Groq::chat()->completions()->create([
        // ... your request parameters
    ]);
} catch (GroqException $e) {
    // Handle the exception, for example, log the error or display a user-friendly message
    Log::error($e->getMessage());
    abort(500, 'An error occurred while processing your request.');
}
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
