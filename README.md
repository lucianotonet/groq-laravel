# Groq Laravel

![Groq Laravel](https://raw.githubusercontent.com/lucianotonet/groq-laravel/v0.0.9/docs/art.png)

[![Latest Stable Version](https://poser.pugx.org/lucianotonet/groq-laravel/v)](https://packagist.org/packages/lucianotonet/groq-laravel) [![Total Downloads](https://poser.pugx.org/lucianotonet/groq-laravel/downloads)](https://packagist.org/packages/lucianotonet/groq-laravel) [![Latest Unstable Version](https://poser.pugx.org/lucianotonet/groq-laravel/v/unstable)](https://packagist.org/packages/lucianotonet/groq-laravel) [![License](https://poser.pugx.org/lucianotonet/groq-laravel/license)](https://packagist.org/packages/lucianotonet/groq-laravel) [![PHP Version Require](https://poser.pugx.org/lucianotonet/groq-laravel/require/php)](https://packagist.org/packages/lucianotonet/groq-laravel)

Groq Laravel is a powerful package for integrating your Laravel applications with the [Groq](https://groq.com/) API, allowing you to leverage ultra-fast AI inference speeds with some of the most popular LLMs, such as Llama3.1 or Mixtral.

Need a "vanilla" PHP version? Try this out: [GroqPHP](https://github.com/lucianotonet/groq-php?tab=readme-ov-file#readme)

## Features

- **Simple and Intuitive Interface:** Interact with the Groq API using the `Groq` facade, simplifying access to chat, translation, audio transcription, function call, and image analysis functionalities.
- **Robust Error Handling:** Efficiently manage communication errors and responses from the Groq API, capturing specific exceptions and providing informative messages.
- **Flexible Configuration:** Define multiple Groq API instances, customize request timeouts, configure caching options, and adjust the package's behavior to suit your needs.
- **Detailed Practical Examples:** Explore code examples that demonstrate how to use the Groq Laravel package in real-world scenarios, including chatbots, audio transcription, and more.
- **Comprehensive Testing:** Ensure the quality and reliability of the package with a suite of tests covering integration, unit testing, and configuration.

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

   ```config
   GROQ_API_KEY=your_api_key_here
   ```

## Usage

Here is a simple example of how to create a chat completion:

   ```php
   use LucianoTonet\GroqLaravel\Facades\Groq;

   $response = Groq::chat()->completions()->create([
      'model' => 'llama-3.1-70b-versatile',  // Check available models at console.groq.com/docs/models
      'messages' => [
         ['role' => 'user', 'content' => 'Hello, how are you?'],
      ],
   ]);

   $response['choices'][0]['message']['content']; // "Hey there! I'm doing great! How can I help you today?"
   ```

## Error Handling

The Groq Laravel package makes it easy to handle errors that may occur when interacting with the Groq API. Use a `try-catch` block to capture and manage exceptions:

   ```php
   try {
      $response = Groq::chat()->completions()->create([
         'model' => 'llama-3.1-8b-instant',
         // ...
      ]);
   } catch (GroqException $e) {
      Log::error('Error in Groq API: ' . $e->getMessage());
      abort(500, 'Error processing your request.');
   }
   ```

Sometimes, the Groq API fails and returns an error message in the response with a failed generation detail. In this case, you can use the `GroqException` class to get the error message:

   ```php
   try {
      $response = Groq::chat()->completions()->create([
         'model' => 'llama-3.1-8b-instant',
         // ...
      ]);
   } catch (GroqException $e) {
      $errorMessage = $e->getFailedGeneration();
      // ...
   }
   ```

## Vision API

The Groq Laravel package also provides access to the Groq Vision API, allowing you to analyze images and extract information from them.

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

**Example of file management:**

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

## Contributing

Contributions are welcome! Follow the guidelines described in the [CONTRIBUTING.md](CONTRIBUTING.md) file.

## License

This package is open-source software licensed under the [MIT license](LICENSE).
