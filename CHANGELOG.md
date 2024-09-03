# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [0.0.8] - 2024-03-XX

### Adicionado
- Novo arquivo `.env.example` com exemplos de configuração da API Groq.
- Suporte para configuração de múltiplas instâncias da API Groq.
- Novos métodos de validação em requisições para facilitar a integração da API.
- Implementação de controle de taxa para evitar abusos da API.
- Adição de testes abrangentes, incluindo testes de integração, unitários e de configuração.
- Novo middleware `GroqRateLimiter` para limitar requisições à API.
- Arquivos de linguagem para suporte a internacionalização de mensagens de erro.

### Alterado
- Atualização do README.md com documentação simplificada e exemplos práticos de uso.
- Refatoração do código para melhorar a legibilidade e manutenibilidade.
- Expansão da configuração com variáveis para ajuste de modelo e limites de taxa.
- Atualização da fachada Groq para acesso simplificado aos métodos da biblioteca GroqPHP.
- Melhoria na flexibilidade de configuração da integração com a API Groq.

### Corrigido
- Correção de problemas de compatibilidade com diferentes versões do Laravel.

### Segurança
- Implementação de melhores práticas de segurança na manipulação de chaves de API.

## [0.0.7] - 2024-XX-XX

### Adicionado
- Versão inicial do pacote Groq Laravel.
- Integração básica com a API Groq.
- Configuração inicial do pacote.

[0.0.8]: https://github.com/lucianotonet/groq-laravel/compare/v0.0.7...v0.0.8
[0.0.7]: https://github.com/lucianotonet/groq-laravel/releases/tag/v0.0.7