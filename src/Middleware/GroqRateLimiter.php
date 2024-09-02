<?php

namespace LucianoTonet\GroqLaravel\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Adicione esta linha

class GroqRateLimiter
{
    public function handle(Request $request, Closure $next)
    {
        $key = 'groq-api:' . $request->ip();
        $maxAttempts = config('groq.rate_limit', 60);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            Log::warning('Limite de requisições excedido para IP: ' . $request->ip());
            return response()->json(['error' => 'Limite de requisições excedido.'], 429);
        }

        RateLimiter::hit($key);

        return $next($request);
    }
}