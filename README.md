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

$response = Groq::chat()->completions()->create([
    'model' => 'meta-llama/llama-4-maverick-17b-128e-instruct', // Or any other model
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

## Advanced Features

### Vision API

The Vision API allows you to analyze images and extract information from them.

**Example of use with image URL:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// ...

$imageUrl = 'https://example.com/image.jpg'; // Replace with your image URL
$prompt = 'Describe the image';

$response = Groq::vision()->analyze($imageUrl, $prompt);

$imageDescription = $response['choices'][0]['message']['content'];

// ... do something with the image description
```

**Example of use with local image file:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// ...

$imagePath = '/path/to/your/image.jpg'; // Replace with the actual path
$prompt = 'What do you see in this image?';

$response = Groq::vision()->analyze($imagePath, $prompt);

$imageAnalysis = $response['choices'][0]['message']['content'];

// ... do something with the image analysis
```

**Remember:**
- The Vision API requires a model compatible with image analysis, such as `llava-v1.5-7b-4096-preview`. You can configure the default model for Vision in the `config/groq.php` configuration file.
- The Vision API is an experimental feature and may not meet expectations, as well as not having long-term support.

## Audio Transcriptions

The Groq Laravel package allows you to transcribe audio using advanced models like Whisper.

**Example of transcribing an audio file:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// Basic transcription
$result = Groq::transcriptions()->create([
    'file' => storage_path('app/audio/recording.mp3'),
    'model' => 'whisper-large-v3'
]);

echo $result['text']; // Transcribed text from the audio
```

**Example with advanced options:**

```php
// Transcription with advanced options
$result = Groq::transcriptions()->create([
    'file' => storage_path('app/audio/recording.mp3'),
    'model' => 'whisper-large-v3',
    'language' => 'en', // Audio language (optional)
    'prompt' => 'Transcription of a business meeting', // Context to improve accuracy
    'temperature' => 0.3, // Lower randomness for more accuracy
    'response_format' => 'verbose_json' // Detailed format with timestamps
]);

// Now you have access to timestamps and segments
echo $result['text']; // Complete text
foreach ($result['segments'] as $segment) {
    echo "From {$segment['start']} to {$segment['end']}: {$segment['text']}\n";
}
```

## Audio Translations

The Groq Laravel package also provides support for direct audio translation to English text.

**Basic example of audio translation:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// Basic translation (always to English)
$result = Groq::translations()->create([
    'file' => storage_path('app/audio/portuguese.mp3'),
    'model' => 'whisper-large-v3'
]);

echo $result['text']; // English text translated from the audio
```

**Example with advanced options:**

```php
// Translation with advanced options
$result = Groq::translations()->create([
    'file' => storage_path('app/audio/french.mp3'),
    'model' => 'whisper-large-v3',
    'prompt' => 'This is a business meeting', // Context in English
    'temperature' => 0.3, // Lower randomness for more accuracy
    'response_format' => 'verbose_json' // Detailed format with timestamps
]);

// Now you have access to timestamps and segments in English
echo $result['text']; // Complete text in English
foreach ($result['segments'] as $segment) {
    echo "From {$segment['start']} to {$segment['end']}: {$segment['text']}\n";
}
```

## Speech API (Text-to-Speech)

The Groq Laravel package also supports Text-to-Speech (TTS) to convert text into spoken audio.

**Basic example of Text-to-Speech:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Storage;

// Basic Text-to-Speech
// Model, voice, and response_format will be taken from your config/groq.php by default.
$speechResponse = Groq::speech()->create([
    'input' => 'Hello from Groq Laravel! This is a text-to-speech test.',
]);

// Save the audio file (e.g., to storage/app/speech_output.wav)
Storage::disk('local')->put('speech_output.wav', $speechResponse->body()); 
// $speechResponse->body() contains the raw audio bytes.
// You can also stream the response using $speechResponse->stream() if needed.
```

**Example with advanced options:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Storage;

// Text-to-Speech with advanced options
$speechResponse = Groq::speech()->create([
    'model' => 'playht/v2/samantha-v2-120ms-lowlatency', // Specify a different model (check Groq documentation for available TTS models)
    'input' => 'This is a test with a different voice, format, and speed.',
    'voice' => 'samantha-playht',   // Specify a different voice
    'response_format' => 'mp3',   // Supported formats: mp3, ogg_vorbis, wav, flac
    'speed' => 0.9                 // Adjust speed (0.25 to 4.0)
]);

// Save the audio file (e.g., to storage/app/speech_output_advanced.mp3)
Storage::disk('local')->put('speech_output_advanced.mp3', $speechResponse->body());
```

**Note:** Refer to the official Groq API documentation for the latest available TTS models, voices, and supported formats. You can set default values in your `config/groq.php` file.

## Step-by-Step Reasoning

Groq Laravel offers support for obtaining responses with step-by-step reasoning, useful for detailed explanations, mathematical problems, or any solution that benefits from a transparent process.

**Example with raw reasoning format:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// Raw format - displays the entire reasoning process
$response = Groq::reasoning()->analyze('How to solve x^2 - 9 = 0?', [
    'model' => 'llama-3.1-8b-instant',
    'reasoning_format' => 'raw'
]);

echo $response['choices'][0]['message']['content'];
// Displays both the reasoning process and the answer
```

**Example with parsed reasoning format:**

```php
// Parsed format - separates reasoning from the final answer
$response = Groq::reasoning()->analyze('What is the capital of France?', [
    'model' => 'llama-3.1-70b-instant',
    'reasoning_format' => 'parsed'
]);

echo "Answer: " . $response['choices'][0]['message']['content'] . "\n";
echo "Reasoning: " . $response['choices'][0]['message']['reasoning'];
// Displays the direct answer (Paris) and the reasoning separately
```

**Example with hidden reasoning:**

```php
// Hidden format - only the final answer
$response = Groq::reasoning()->analyze('Calculate the area of a circle with radius 5cm', [
    'model' => 'llama-3.1-8b-instant',
    'reasoning_format' => 'hidden'
]);

echo $response['choices'][0]['message']['content'];
// Displays only the final answer, without the reasoning process
```

## Advanced Completions

Groq Laravel supports advanced completion features, including image analysis and response streaming.

**Example with image input:**

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// Completions with image
$response = Groq::completions()->create([
    'model' => 'llava-v1.5-7b-4096-preview',
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                ['type' => 'text', 'text' => 'What is in this image?'],
                [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => 'https://example.com/image.jpg'
                    ]
                ]
            ]
        ]
    ]
]);

echo $response['choices'][0]['message']['content'];
```

**Example with streaming:**

```php
// Completions with streaming
$stream = Groq::completions()->create([
    'model' => 'llama-3.1-8b-instant',
    'messages' => [
        ['role' => 'user', 'content' => 'Tell a short story about a robot']
    ],
    'stream' => true
]);

// Process the response in stream (useful for real-time interfaces)
foreach ($stream->chunks() as $chunk) {
    if (isset($chunk['choices'][0]['delta']['content'])) {
        echo $chunk['choices'][0]['delta']['content'];
        // Send to client in real-time (in real applications)
        ob_flush();
        flush();
    }
}
```

## File Management

Groq Laravel allows you to manage files for use with the API.

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// List files
$files = Groq::files()->list();

// Upload a file
$file = Groq::files()->upload(storage_path('app/data/document.txt'), 'assistants');

// Retrieve file information
$fileInfo = Groq::files()->retrieve($file['id']);

// Delete a file
Groq::files()->delete($file['id']);
```

## More examples

As the GroqLaravel package is a wrapper for the GroqPHP package, you can check more examples in the [GroqPHP repository](https://github.com/lucianotonet/groq-php?tab=readme-ov-file#readme).

## Testing

Testing is an essential part of quality software development. The Groq Laravel package includes a test suite that covers integration, unit, and configuration. To run the tests, follow the steps below:

1. **Install the project dependencies:**

   ```bash
   composer install
   ```

2. **Run the tests:**

   ```bash
   vendor/bin/phpunit ./tests/Feature
   ```

   or individually:

   ```bash
   vendor/bin/phpunit ./tests/Feature/FacadeTest.php
   ```

## Credits

- [Luciano Tonet](https://github.com/lucianotonet)
- [All Contributors](../../contributors)

## Contributing

Contributions are welcome! Follow the guidelines described in the [CONTRIBUTING.md](CONTRIBUTING.md) file.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
