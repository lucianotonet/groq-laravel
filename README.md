# Groq Laravel

[![Latest Stable Version](https://poser.pugx.org/lucianotonet/groq-laravel/v/stable)](https://packagist.org/packages/lucianotonet/groq-laravel)
[![Total Downloads](https://poser.pugx.org/lucianotonet/groq-laravel/downloads)](https://packagist.org/packages/lucianotonet/groq-laravel)
[![License](https://poser.pugx.org/lucianotonet/groq-laravel/license)](https://packagist.org/packages/lucianotonet/groq-laravel)

Groq Laravel é um pacote poderoso para integração entre suas aplicações Laravel a API da [Groq](https://groq.com/), permitindo que você aproveite velocidades ultra-rápidas de inferência de IA com alguns dos LLMs mais populares, como o Llama3.1 ou Mixtral.

## Features

- **Interface Simples e Intuitiva:** Interaja com a API Groq usando a facade `Groq`, simplificando o acesso às funcionalidades de chat, tradução e transcrição de áudio e chamadas à funções.
- **Tratamento Robusto de Erros:** Gerencie eficientemente erros de comunicação e respostas da API Groq, capturando exceções específicas e fornecendo mensagens informativas.
- **Configuração Flexível:** Defina várias instâncias da API Groq, personalize timeouts de requisição, configure opções de cache e ajuste o comportamento do pacote conforme suas necessidades.
- **Exemplos Práticos Detalhados:** Explore exemplos de código que demonstram como usar o pacote Groq Laravel em cenários reais, incluindo chatbots, transcrição de áudio e muito mais.
- **Testes Abrangentes:** Garanta a qualidade e confiabilidade do pacote com um conjunto de testes que cobrem aspectos de integração, testes unitários e configuração.

## Instalação

1. Instale o pacote via Composer:

   ```bash
   composer require lucianotonet/groq-laravel
   ```

2. Publique o arquivo de configuração:

   ```bash
   php artisan vendor:publish --provider="LucianoTonet\GroqLaravel\GroqServiceProvider"
   ```

3. Configure suas credenciais da API Groq no arquivo `.env`:

   ```
   GROQ_API_KEY=your_api_key_here
   GROQ_API_BASE=https://api.groq.com/openai/v1
   ```

4. (Opcional) Configure o cache definindo as seguintes variáveis de ambiente no arquivo `.env`:

   ```
   GROQ_CACHE_DRIVER=file
   GROQ_CACHE_TTL=3600
   ```

5. Importe a facade `Groq` em suas classes:

   ```php
   use LucianoTonet\GroqLaravel\Facades\Groq;
   ```

## Uso

Aqui está um exemplo simples de como criar uma conclusão de chat:

```php
$response = Groq::chat()->completions()->create([
    'model' => 'llama-3.1-8b-instant',
    'messages' => [
        ['role' => 'user', 'content' => 'Olá, como você está?'],
    ],
]);
```

## Tratamento de Erros

O pacote Groq Laravel facilita o tratamento de erros que podem ocorrer ao interagir com a API Groq. Use um bloco `try-catch` para capturar e gerenciar exceções:

```php
try {
    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        // ...
    ]);
} catch (GroqException $e) {
    Log::error('Erro na API Groq: ' . $e->getMessage());
    abort(500, 'Erro ao processar sua solicitação.');
}
```

## Testes

Os testes são uma parte essencial do desenvolvimento de software de qualidade. O pacote Groq Laravel inclui uma suíte de testes que cobre integração, unidade e configuração. Para executar os testes, siga os passos abaixo:

1. **Instale as dependências do projeto:**

   ```bash
   composer install
   ```

2. **Execute os testes:**

   ```bash
   vendor/bin/phpunit ./tests/Feature
   ```

   ou individualmente:

   ```bash
   vendor/bin/phpunit ./tests/Feature/FacadeTest.php
   ```

## Contribuindo

Contribuições são bem-vindas! Siga as diretrizes descritas no arquivo [CONTRIBUTING.md](CONTRIBUTING.md).

## Licença

Este pacote é um software de código aberto licenciado sob a [licença MIT](LICENSE).
