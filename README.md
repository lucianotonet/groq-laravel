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
