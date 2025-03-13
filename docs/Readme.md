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

## Implementações para Processamento de Conversões

### 1. Script de Processamento de Pedidos (order.php)

Foi implementado um script `order.php` para processar os pedidos enviados pelo
formulário:

```php
<?php
// Arquivo de processamento de pedidos simples
header('Content-Type: text/html; charset=utf-8');

// Logs dos dados recebidos para debug
$log_file = __DIR__ . '/logs/order_log.txt';
$log_dir = dirname($log_file);

if (!file_exists($log_dir)) {
    mkdir($log_dir, 0755, true);
}

// Registra os dados recebidos
$data = date('Y-m-d H:i:s') . " - ORDER RECEIVED\n";
$data .= "POST: " . print_r($_POST, true) . "\n";
$data .= "GET: " . print_r($_GET, true) . "\n";
$data .= "-------------------------------------\n";

file_put_contents($log_file, $data, FILE_APPEND);

// Retorna uma resposta simples
echo '<html>
<head>
    <title>Pedido Recebido</title>
</head>
<body>
    <h1>Pedido Processado com Sucesso</h1>
    <p>Obrigado pelo seu pedido!</p>
</body>
</html>';
```

### 2. Script Alternativo para Criação de Leads (create_lead.php)

Para testes e criação direta de leads, foi implementado o script
`create_lead.php`:

```php
<?php
// Arquivo para criar um lead diretamente
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'settings.php';
require_once 'db.php';

// Dados do lead
$subid = uniqid('test_');
$name = 'Cliente Teste Direto';
$phone = '11987654321';
$email = 'teste@exemplo.com';
$landing = 'offer1';

// Criar o lead com status inicial
$result = add_lead($subid, $name, $phone, 'Lead');

echo "Resultado da criação do lead: ";
var_dump($result);
echo "<br>";
echo "SubID gerado: " . $subid;
echo "<br><br>";

// Exibir informações úteis para criar um postback manualmente
echo "Para atualizar este lead para Purchase, use:<br>";
echo "curl -v \"http://localhost:8000/postback.php?subid={$subid}&status=Purchase&payout=99.99\"";
?>
```

## Como Testar Conversões

### 1. Método via Formulário

- Preencher o formulário na página `offer1/index.html`
- Enviar os dados para processamento

### 2. Método via Script Direto

- Acessar `create_lead.php` para gerar um lead diretamente
- Usar o comando curl exibido para atualizar o status

### 3. Método via Postback Manual

```bash
curl -v "http://localhost:8000/postback.php?subid=SUBID_AQUI&status=Purchase&payout=99.99"
```

Onde `SUBID_AQUI` é o identificador de um lead existente

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
- `order.php`: Processa pedidos da página de oferta
- `create_lead.php`: Ferramenta para criação direta de leads para testes

# Documentação do Sistema de Captura de Email e Integração com Webhook

Este repositório contém a documentação detalhada do sistema de captura de email,
registro de leads e integração com webhooks externos implementado no site.

## Visão Geral do Sistema

O sistema implementa um fluxo completo para captura de emails via modal,
registro de leads no banco de dados local e integração com webhook externo do
Autonami. O sistema também inclui redirecionamento com preservação de parâmetros
de URL para rastreamento de campanhas.

## Componentes Principais

### Frontend

- [Modal de Captura de Email](offer2/email-modal.md): Implementação do modal
  interativo para captura de email

### Backend

- [Processamento de Leads por Email](send/email-lead.md): Sistema de
  processamento e armazenamento de leads de email
- [Sistema de Proxy para Webhook](send/webhook-proxy.md): Implementação do proxy
  para contornar limitações de CORS

### Utilitários

- [Sistema de Redirecionamento](offer2/redirect-system.md): Sistema de
  redirecionamento com preservação de parâmetros de URL

## Fluxo de Operação Completo

1. Usuário acessa a landing page com parâmetros de rastreamento
2. Usuário clica no botão CTA "CONHECER O MÉTODO AGORA"
3. Modal de captura de email é exibido
4. Usuário insere email e submete o formulário
5. Frontend valida o email e envia para o backend
6. Backend registra o email no banco de dados local
7. Backend envia os dados para o webhook externo do Autonami
8. Backend retorna status de sucesso para o frontend
9. Frontend exibe mensagem de confirmação
10. Após breve delay, usuário é redirecionado para página de destino
11. Todos os parâmetros de rastreamento são preservados na URL de destino

## Estrutura da Documentação

A documentação está organizada seguindo a mesma estrutura de diretórios do
código-fonte:

```
docs/
├── README.md
├── offer2/
│   ├── email-modal.md
│   └── redirect-system.md
└── send/
    ├── email-lead.md
    └── webhook-proxy.md
```

Cada arquivo de documentação segue uma estrutura padronizada com as seguintes
seções:

- To Fix: Funcionalidades que precisam ser corrigidas
- To Do: Funcionalidades que precisam ser implementadas
- Test: Funcionalidades em fase de teste
- Done: Funcionalidades implementadas e funcionando corretamente
