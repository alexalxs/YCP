# Yellow Cloaker Documentation

This documentation provides instructions for using and configuring the Yellow
Cloaker system.

## Access to Configuration

All settings are now managed through the web interface available at:

```
/admin?password=12345
```

**Important Note**: Please change the default password immediately after
installation for security purposes.

## System Requirements

- PHP version 7.2 or higher
- HTTPS certificates for all your domains
- No WordPress installation required

## Traffic Distribution System (TDS)

The system offers a complete solution for managing and distributing web traffic
with the following functionalities:

### User Flow Management

- **White/Black Mode**: Dual redirection system that allows directing visitors
  to different destinations based on configurable criteria.
- **303 Redirections**: HTTP redirection configuration to specific pages.
- **Domain Filtering**: Ability to filter access based on specific domains.

### Security and Filters

- **JavaScript Verifications**: Ability to perform security checks such as
  audiocontext and timezone.
- **TDS Filters**: Configuration of filters by countries, operating systems,
  IPs, user agents, ISPs, and VPN/Tor detection.
- **URL Parameters**: Support for filtering based on specific parameters in the
  URL.

### Tracking and Analysis

- **SubID System**: Support for multiple sub-tracking identifiers with parameter
  rewriting capability.
- **Statistics**: Password-protected panel for viewing performance with support
  for multiple subnames.
- **Postbacks**: Event notification system (Lead, Purchase, Reject, Trash)
  including support for S2S webhooks.

### Marketing Platform Integration

- **Tracking Pixels**: Support for Facebook Pixel, TikTok Pixel, Yandex Metrika,
  and Google Tag Manager.
- **Customizable Events**: Configuration of conversion and content view events.

### UX/UI Features

- **Interactive Scripts**: Functionalities such as custom back button, text copy
  deactivation, phone masks.
- **Optimization**: Lazy loading of images for better performance.
- **Interactive Widgets**: Comebacker, callbacker, and "added to cart"
  notification system.

### Content Management

- **Thank You Page Customization**: Ability to configure thank you pages with
  upsell options.
- **Conversion Script Integration**: Ability to integrate custom scripts for
  conversion tracking.

## Important Implementation Notes

- Always use relative links within the project
- The project has no relationship with WordPress
- There should be no redirects within the system
- Do not include index.php inside offer or white page folders as they are
  managed by the end user
- The YellowCloaker-master folder is for research purposes only

## Testing Your Implementation

You can test the implementation using curl:

```
curl -s "http://localhost:8000"
```

Remember to test all navigation paths created between pages.

# Documentação Técnica: YCP + Plataforma de Afiliados + Fonte de Tráfego

Esta pasta contém diagramas e documentação técnica sobre a integração entre o
Yellow Cloaker Platform (YCP), plataformas de afiliados e fontes de tráfego.

## Diagramas Disponíveis

1. **[Diagrama de Sequência: Fluxo de Conversão](flow-diagram.md)**
   - Visão geral do fluxo completo desde o clique no anúncio até o registro
     final
   - Mostra a sequência de interações entre componentes do sistema
   - Ilustra mudanças de estado durante o processo

2. **[Diagrama Técnico: Implementação e Estrutura de Dados](technical-flow.md)**
   - Detalha a implementação técnica e a estrutura de dados
   - Foco nas operações de banco de dados e formatos de dados trocados
   - Especifica os JSON e formatos de armazenamento

3. **[Diagrama de Estado: Ciclo de Vida do Lead](lead-status-flow.md)**
   - Mostra as mudanças de estado de um lead (Lead → Purchase/Reject/Trash)
   - Explica detalhadamente cada estado e suas transições
   - Inclui detalhes sobre a implementação no código

## Caso de Uso Principal

O caso de uso documentado mostra o fluxo completo de conversão em um cenário
bem-sucedido:

1. Um usuário clica em um anúncio de uma fonte de tráfego
2. O YCP processa o clique e direciona o usuário para uma página de oferta
3. O usuário preenche e envia um formulário na página de oferta
4. O sistema registra um lead com status inicial "Lead"
5. O usuário é redirecionado para uma página de agradecimento
6. Posteriormente, o status do lead é atualizado para "Purchase" via postback
7. O sistema envia postbacks para plataformas de afiliados
8. As estatísticas são atualizadas no painel administrativo

## Estrutura de Dados Principal

### Lead (SleekDB - JSON)

```json
{
  "subid": "123abc",
  "time": 1741890052,
  "name": "Teste Usuario",
  "phone": "11987654321",
  "email": "teste@exemplo.com",
  "status": "Purchase",
  "payout": "99.99",
  "fbp": "fb.1.123",
  "fbclid": "abc123",
  "preland": "preland1",
  "land": "offer1"
}
```

### Configuração de Postback (settings.json)

```json
"postback": {
  "lead": "Lead",
  "purchase": "Purchase",
  "reject": "Reject",
  "trash": "Trash",
  "s2s": [
    {
      "url": "https://tracker.com/postback?sid={subid}&status={status}",
      "method": "GET",
      "events": ["Lead", "Purchase"]
    }
  ]
}
```

## Como Visualizar os Diagramas

Os diagramas estão no formato Mermaid, que pode ser visualizado:

1. Diretamente no GitHub, que renderiza nativamente diagramas Mermaid
2. Em editores que suportam Mermaid, como VS Code com extensões apropriadas
3. No visualizador online Mermaid Live Editor: https://mermaid.live/

## Arquivos Relacionados no Código

- `send.php`: Processa formulários e cria leads
- `postback.php`: Gerencia atualizações de status e envio de postbacks
- `db.php`: Contém funções para manipulação de leads no banco de dados
- `settings.json`: Configurações, incluindo definições de postback
- `statistics.php`: Exibe estatísticas de conversão
