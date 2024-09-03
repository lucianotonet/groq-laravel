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

These are just a few examples of how to use the Groq Laravel package. Explore the documentation and experiment with different approaches to integrate the Groq API into your projects.