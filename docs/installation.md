---
title: "Groq Laravel Installation"
---

# Installation

Follow these steps to install the Groq Laravel package in your application:

1. **Installation via Composer:**

   ```bash
   composer require lucianotonet/groq-laravel
   ```

2. **Publishing the Configuration File:**

   ```bash
   php artisan vendor:publish --provider="LucianoTonet\GroqLaravel\GroqServiceProvider"
   ```

   This will create the `config/groq.php` file in your project.

3. **Configuring Groq API Credentials:**

   In the `.env` file, define the following environment variables:

   ```
   GROQ_API_KEY=your_api_key_here
   GROQ_API_BASE=https://api.groq.com/openai/v1
   ```

4. **Cache Configuration (Optional):**

   If you want to use caching, define the environment variables in the `.env` file:

   ```
   GROQ_CACHE_DRIVER=file
   GROQ_CACHE_TTL=3600 
   ```

   * `GROQ_CACHE_DRIVER`: Cache driver (e.g., `file`, `redis`, etc.).
   * `GROQ_CACHE_TTL`: Cache time-to-live in seconds.

5. **Importing the `Groq` Facade:**

   To use the `Groq` facade in your classes, add the following `use` statement:

   ```php
   use LucianoTonet\GroqLaravel\Facades\Groq;
   ```

You're all set! Now you can start using the Groq Laravel package in your application.