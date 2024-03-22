# Laravel Groq Package

This package provides integration for using the lucianotonet/groq-php package within Laravel 11 applications.

## Installation

You can install the package via composer:

```bash
composer require lucianotonet/groq-laravel
```

## Usage

### Groq Facade

You can use the `Groq` facade to interact with the Groq package:

```php
use LucianoTonet\GroqLaravel\Facades\Groq;

// Example usage
$result = Groq::query('your_groq_query_here');
```

### GroqServiceProvider

The `GroqServiceProvider` is automatically registered by Laravel and provides the binding for the `Groq` class:

```php
use LucianoTonet\GroqLaravel\Groq;

// Example usage within a service or controller
$groq = app('Groq');
$result = $groq->query('your_groq_query_here');
```

## License

This package is open-sourced software licensed under the MIT license. See the [LICENSE](LICENSE) file for more information.
