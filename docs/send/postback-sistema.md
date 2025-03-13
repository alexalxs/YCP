# Sistema de Postback para Rastreamento de Conversões

## To Fix

- Implementar verificação adicional de segurança via token de autenticação
- Adicionar suporte para callbacks específicos por campanha
- Melhorar a documentação de erros para facilitar o diagnóstico

## To Do

- Implementar notificações por webhook para conversões importantes
- Adicionar suporte para confirmação de recebimento (ACK) de postbacks
- Criar um dashboard para visualização de conversões em tempo real

## Test

- Verificar se o sistema registra corretamente diferentes tipos de eventos
  (lead, venda, etc.)
- Testar a integração com diferentes plataformas de afiliados
- Validar o funcionamento do registro de eventos em cenários de falha de conexão

## Done

### Estrutura do Sistema

O sistema de postback implementa um mecanismo para rastrear conversões e eventos
importantes que ocorrem após o registro inicial do lead. Ele permite que
parceiros, plataformas de afiliados e outros sistemas externos notifiquem nossa
plataforma sobre eventos como:

- Vendas confirmadas
- Pagamentos aprovados
- Cancelamentos
- Upgrades de plano
- Outros eventos personalizados

### Funcionalidades

- **Recebimento de Notificações**: Endpoint dedicado para receber notificações
  de postback de sistemas externos
- **Validação de Dados**: Verificação da integridade e autenticidade dos dados
  recebidos
- **Rastreamento de Conversões**: Registro detalhado de cada evento de conversão
  no banco de dados
- **Atribuição de Comissões**: Suporte para atribuição de comissões com base em
  postbacks
- **Registro de Status**: Atualização do status do lead com base nos eventos
  recebidos
- **Logging Detalhado**: Registro de todas as solicitações de postback para fins
  de auditoria

### Fluxo Operacional

1. Sistema externo envia uma solicitação HTTP para o endpoint de postback
2. O sistema valida a origem e a autenticidade da solicitação
3. Os dados do evento são extraídos e validados
4. O sistema identifica o lead relacionado usando o ID ou SubID
5. O evento de conversão é registrado no banco de dados
6. O status do lead é atualizado conforme necessário
7. Uma resposta de confirmação é enviada ao sistema externo
8. Ações adicionais podem ser acionadas com base no tipo de evento
   (notificações, etc.)

### Parâmetros Aceitos

| Parâmetro        | Tipo    | Descrição                                             |
| ---------------- | ------- | ----------------------------------------------------- |
| `lead_id`        | string  | ID único do lead no sistema                           |
| `subid`          | string  | ID de rastreamento alternativo                        |
| `event`          | string  | Tipo de evento (venda, pagamento, cancelamento, etc.) |
| `amount`         | decimal | Valor da transação (se aplicável)                     |
| `status`         | string  | Status da transação                                   |
| `transaction_id` | string  | ID da transação no sistema externo                    |
| `timestamp`      | integer | Timestamp Unix do evento                              |
| `sign`           | string  | Assinatura de verificação para validação              |

### Exemplos de Uso

**Notificação de Venda Concluída:**

```
GET /postback.php?lead_id=67ce55635f424&event=sale&amount=97.00&status=approved&transaction_id=TX123456
```

**Notificação de Cancelamento:**

```
GET /postback.php?subid=f6e1d85fe8eba6952b8d9ae2386953a3&event=cancel&transaction_id=TX123456
```

### Integração com Plataformas de Afiliados

O sistema suporta integração direta com as seguintes plataformas:

- Hotmart
- Monetizze
- Kiwify
- Perfectpay
- Plataformas personalizadas via API

### Implementação Técnica

O sistema é implementado no arquivo `postback.php` e utiliza funções auxiliares
de outros componentes:

```php
<?php
// Recebe uma notificação de postback
function process_postback($lead_id, $event, $status, $amount = 0) {
    // Validar os dados recebidos
    // Identificar o lead no banco de dados
    // Registrar o evento de conversão
    // Atualizar o status do lead
    // Retornar confirmação
}
?>
```

### Considerações de Segurança

- Todas as solicitações são validadas usando uma assinatura digital ou token
  secreto
- As origens das solicitações são verificadas para evitar postbacks fraudulentos
- Os dados sensíveis são armazenados de forma segura no banco de dados
- O sistema registra tentativas de acesso não autorizado para detecção de
  fraudes
