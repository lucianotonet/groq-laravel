---
title: "Groq Laravel Examples"
---

# Practical Examples

Explore practical examples of how to use the Groq Laravel package in your applications:

## 1. Creating a Simple Chatbot

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// ...

public function chatbot(Request $request)
{
    $userMessage = $request->input('message');

    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a friendly chatbot.'],
            ['role' => 'user', 'content' => $userMessage],
        ],
    ]);

    $botResponse = $response['choices'][0]['message']['content'];

    return response()->json(['message' => $botResponse]);
}
```

## 2. Transcribing an Audio File

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// ...

public function transcribe(Request $request)
{
    $audioFile = $request->file('audio');

    $transcription = Groq::audioTranscription([
        'file' => $audioFile,
        'model' => 'whisper-large-v3',
    ]);

    $text = $transcription['text'];

    // ... save transcription to database or perform other actions
}
```

## 3. Generating Product Descriptions with a Trait

```php
use LucianoTonet\GroqLaravel\Traits\HasGroqDescription;

class Product extends Model
{
    use HasGroqDescription;

    // ...

    public function generateDescription()
    {
        $this->description = $this->getGroqDescription('Create a short and catchy description for this product:');
        $this->save();
    }
}
```

## 4. Implementing a Question and Answer System

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// ...

public function answer(Request $request)
{
    $question = $request->input('question');

    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a question and answer system about [your topic here].'],
            ['role' => 'user', 'content' => $question],
        ],
    ]);

    $answer = $response['choices'][0]['message']['content'];

    return response()->json(['answer' => $answer]);
}
```

## 5. Handling API Errors

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// ...

try {
    $response = Groq::chat()->completions()->create([
        // ...
    ]);
} catch (\Exception $e) {
    Log::error('Error in Groq API: ' . $e->getMessage());

    // Return a friendly message to the user
    return response()->json(['error' => 'An error occurred while processing your request.'], 500);
}
```

## 6. Analyzing an Image with Vision

To analyze an image with the Groq Vision API, you can use the `vision()` method of the `Groq` facade. This method requires two parameters:

- `$imagePathOrUrl`: The path to the local image file or the URL of the image.
- `$prompt`: The question or context for the image analysis.

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

## 7. More examples

These are just a few examples of how to use the Groq Laravel package. Explore the documentation and experiment with different approaches to integrate the Groq API into your projects.