---
title: "Groq Facade"
---

# Groq Facade

The `Groq` facade provides a fluent interface for interacting with the Groq API in your Laravel applications.

## Available Methods

### `chat(): \LucianoTonet\GroqPHP\Chat`

Returns an instance of the `Chat` class, which allows interaction with the chat functionalities of the Groq API.

**Example:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

$response = Groq::chat()->completions()->create([
    'messages' => [
        ['role' => 'user', 'content' => 'Hello, how are you?'],
    ],
]);
```

### `audio(): \LucianoTonet\GroqPHP\Audio`

Returns an instance of the `Audio` class, which allows the use of audio functionalities of the Groq API.

**Example:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

$transcription = Groq::audio()->transcriptions()->create([
    'file' => $request->file('audio'),
    'model' => 'whisper-large-v3', 
]);
```

### `models(): \LucianoTonet\GroqPHP\Models`

Returns an instance of the `Models` class, which allows access to information about the available models in the Groq API.

**Example:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

$models = Groq::models()->list();
```

## Helper Methods

The `Groq` facade also offers helper methods to perform common tasks more concisely:

### `chat()->completions()->create(array $params): array`

Creates a new chat completion with the provided parameters.

**Example:**

```php
$response = Groq::chat()->completions()->create([
    'model' => 'llama-3.1-8b-instant',
    'messages' => [
        ['role' => 'user', 'content' => 'Tell me a joke.'],
    ],
]);
```

### `audioTranscription(array $params): array`

Transcribes an audio file with the provided parameters.

**Example:**

```php
$transcription = Groq::audioTranscription([
    'file' => $request->file('audio'),
    'model' => 'whisper-large-v3', 
]);
```

## Error Handling

The `Groq` facade throws a `GroqException` when an error occurs during API communication. You can catch this exception to handle errors gracefully:

```php
use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqPHP\GroqException;

try {
    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        // ...
    ]);
} catch (GroqException $e) {
    // Handle the error
    Log::error('Groq API Error: ' . $e->getMessage());
    // Return an error response or take appropriate action
}
```

By using the `Groq` facade, you can easily integrate Groq API functionality into your Laravel application with a clean and intuitive interface.
