---
title: "Groq Laravel Tests"
---

# Testes

A garantia da qualidade e funcionalidade do pacote Groq Laravel é fundamental para a confiança dos desenvolvedores. Para atingir esse objetivo, foram implementados vários testes que abrangem diferentes aspectos do pacote.

## O que são testes?

Os testes são uma forma de garantir que o pacote esteja funcionando corretamente e que as funcionalidades sejam executadas como esperado. Eles ajudam a identificar e corrigir erros antes que o software seja lançado.

## Tipos de Testes

### Testes de Integração

Os testes de integração verificam se as diferentes partes do pacote estão funcionando juntas corretamente. Eles são essenciais para garantir que os componentes do sistema interajam conforme o esperado.

### Testes de Unidade

Os testes de unidade verificam se as funções e métodos individuais do pacote estão funcionando corretamente. Eles são importantes para garantir que cada parte do código funcione isoladamente.

### Testes de Configuração

Os testes de configuração verificam se as configurações do pacote estão sendo carregadas corretamente. Eles garantem que as variáveis de ambiente e os arquivos de configuração estejam configurados corretamente.

## Por que os testes são importantes?

Os testes são importantes porque garantem que o pacote esteja funcionando corretamente e que as funcionalidades sejam executadas como esperado. Isso ajuda a evitar erros e problemas que podem afetar a experiência do usuário.

## Como executar os testes?

Para executar os testes, siga os passos abaixo:

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
   vendor/bin/phpunit ./tests/Feature/ConfigTest.php
   ...
   ```

## Exemplos de Testes

### Teste de API Real

Este teste verifica a integração com a API Groq real. Certifique-se de ter as variáveis de ambiente configuradas corretamente.

```php
public function testRealApiCall()
{
    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'user', 'content' => 'Olá, mundo!'],
        ],
    ]);
    
    $this->assertArrayHasKey('choices', $response);
    $this->assertNotEmpty($response['choices']);
}
```

### Teste de API Mockada

Este teste usa respostas mockadas para verificar a funcionalidade sem fazer chamadas reais à API.

```php
public function testMockedApiCall()
{
    // Carregar a resposta mockada do arquivo
    $mockResponse = json_decode(Storage::disk('local')->get('mocks/real_api_response.json'), true);
    // Mockar a chamada da API
    Groq::shouldReceive('chat->completions->create')->andReturn($mockResponse);
    
    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello, how are you?']
        ],
    ]);
    $this->assertEquals($mockResponse, $response);
    
    // Verificar a resposta mockada
    $this->assertArrayHasKey('choices', $mockResponse);
    $this->assertNotEmpty($mockResponse['choices']);
}
```

### Teste de Configuração

Este teste verifica se as configurações do pacote estão sendo carregadas corretamente.

```php
public function testConfigValues()
{
    $config = config('groq');
    $this->assertNotEmpty($config['api_key']);
    $this->assertEquals('https://api.groq.com/openai/v1', $config['api_base']);
}
```

### Teste de Facade

Este teste verifica se a facade `Groq` está funcionando corretamente.

```php
public function testFacadeChatCompletions()
{
    $response = Groq::chat()->completions()->create([
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'user', 'content' => 'Olá, mundo!'],
        ],
    ]);
    
    $this->assertArrayHasKey('choices', $response);
    $this->assertNotEmpty($response['choices']);
}
```

## Resultados dos Testes

Os resultados dos testes são importantes para garantir que o pacote esteja funcionando corretamente. Se os testes falharem, é importante investigar e corrigir os erros para garantir que o pacote esteja funcionando corretamente.

## Conclusão

Os testes são uma parte essencial do desenvolvimento de software de qualidade. Eles ajudam a garantir que o pacote Groq Laravel funcione conforme o esperado e forneça uma experiência confiável para os desenvolvedores que o utilizam. Certifique-se de executar os testes regularmente e de manter a cobertura de testes ao adicionar novas funcionalidades ao pacote.
