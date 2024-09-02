<?php

namespace LucianoTonet\GroqLaravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroqRequest extends FormRequest
{
    public function rules()
    {
        return [
            'messages' => 'required|array',
            'messages.*.role' => 'required|string|in:user,system,assistant,tool',
            'messages.*.content' => 'required|string|max:500',
            'tool_choice' => 'nullable|string|in:auto,manual',
            'tools' => 'nullable|array',
            'tools.*.type' => 'required_with:tools|string|in:function',
            'tools.*.function.name' => 'required_with:tools|string',
            'tools.*.function.description' => 'required_with:tools|string',
            'tools.*.function.parameters' => 'required_with:tools|array',
            'tools.*.function.parameters.type' => 'required_with:tools|string|in:object',
            'tools.*.function.parameters.properties' => 'required_with:tools|array',
            'tools.*.function.parameters.required' => 'required_with:tools|array',
            'tools.*.function.parameters.default' => 'nullable|array',
            'audio' => 'nullable|array', // Adicionando suporte para áudio
            'audio.file' => 'required_with:audio|file|mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm', // Arquivo de áudio
            'audio.model' => 'required_with:audio|string', // Modelo de transcrição ou tradução
            'audio.response_format' => 'nullable|string|in:json,verbose_json,text', // Formato de resposta
            'audio.language' => 'nullable|string|size:2', // Código de idioma ISO 639-1
            'audio.prompt' => 'nullable|string|max:500', // Texto opcional para contexto
        ];
    }

    public function authorize()
    {
        return true;
    }
}