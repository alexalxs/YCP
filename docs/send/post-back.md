<think>O usuário está solicitando duas coisas:

1. Criar documentação sobre o sistema de postback baseado no sistema atual, sem sugerir mudanças
2. Fazer uma requisição para registrar a compra do lead com ID 67ce55635f424

Para atender a primeira parte da solicitação, preciso entender como funciona o sistema de postback atual. Vou analisar o código disponível para identificar como o sistema de postback está implementado.

Para a segunda parte, preciso entender como fazer uma requisição para registrar a compra de um lead específico. Precisarei verificar qual endpoint ou método é usado para isso no sistema atual.

Vamos começar analisando os arquivos para entender o sistema de postback.</think># Documentação do Sistema de Postback

Vou criar uma documentação sobre o sistema de postback atual e depois fazer uma requisição para registrar a compra do lead solicitado.

Search files...
Agora vou verificar como o sistema atualiza o status de um lead para uma compra, consultando a função `update_lead` mencionada no arquivo `postback.php`:

Search files...
Agora vou verificar os valores das variáveis de configuração para os nomes de status para garantir que minha documentação esteja correta:

Read file: settings.php
Vamos verificar as configurações específicas do postback:

Read file: settings.json
Agora vou fazer uma requisição para registrar a compra do lead com o ID especificado.

```bash
curl "http://localhost:8000/postback.php?subid=67ce55635f424&status=Purchase&payout=50" -v
```
Vou verificar se os logs de postback foram criados:

```bash
ls -la pblogs/ && cat pblogs/13.03.25.pb.log
```
Agora vou verificar o estado do lead no banco de dados para confirmar que foi atualizado:

```bash
cd logs && ls -la && ls -la leads
```
# Documentação do Sistema de Postback

## Visão Geral

O sistema de postback é um mecanismo crucial para o rastreamento de conversões e atualizações de status dos leads no fluxo de aquisição de clientes. Ele permite que o sistema atualize o status dos leads ao longo do funil de vendas, registrando eventos como "Lead", "Purchase", "Reject" e "Trash".

## Funcionalidades Principais

1. **Atualização de Status de Leads**: Permite atualizar o status de um lead existente através de requisições HTTP.
2. **Registro de Pagamento (Payout)**: Registra o valor monetário associado às conversões.
3. **Encaminhamento S2S (Server-to-Server)**: Encaminha eventos de conversão para sistemas externos através de webhooks.
4. **Registro de Logs**: Mantém registros detalhados de todas as requisições de postback para auditoria e solução de problemas.

## Estrutura do Sistema

### 1. Configurações

As configurações do sistema de postback são definidas no arquivo `settings.json` na seção "postback":

```json
"postback": {
    "lead": "Lead",
    "purchase": "Purchase",
    "reject": "Reject",
    "trash": "Trash",
    "s2s": [
        {
            "url": "",
            "method": "POST",
            "events": [
                "Lead",
                "Purchase"
            ]
        }
    ]
}
```

Onde:
- `lead`, `purchase`, `reject`, `trash`: Define os nomes dos estados para referência interna
- `s2s`: Configurações para encaminhamento de eventos para sistemas externos (webhooks)

### 2. Arquivo de Processamento

O processamento de postback é realizado pelo arquivo `postback.php`, que:

1. Recebe os parâmetros da requisição (`subid`, `status`, `payout`)
2. Valida a existência desses parâmetros
3. Converte o status externo para o formato interno
4. Atualiza o status do lead no banco de dados
5. Envia notificações S2S para sistemas externos, se configurado
6. Registra logs detalhados das operações

### 3. Parâmetros da Requisição

Para atualizar o status de um lead, o sistema aceita os seguintes parâmetros:

- **subid** (obrigatório): Identificador único do lead
- **status** (obrigatório): Novo status do lead (Lead, Purchase, Reject, Trash)
- **payout** (obrigatório): Valor monetário associado à conversão

### 4. Fluxo de Status dos Leads

Os leads passam pelos seguintes estados:

1. **Lead**: Estado inicial, indicando que um potencial cliente forneceu suas informações
2. **Purchase**: O lead foi convertido em uma venda confirmada
3. **Reject**: O lead foi rejeitado (por exemplo, cliente declinou a oferta)
4. **Trash**: O lead foi descartado (por exemplo, dados inválidos ou suspeita de fraude)

### 5. Webhooks S2S

O sistema permite configurar webhooks para enviar notificações S2S para sistemas externos. Para cada evento configurado, o sistema:

1. Verifica se o evento deve ser processado (com base na configuração)
2. Substitui macros na URL configurada (como {subid}, {status}, etc.)
3. Envia a requisição HTTP (GET ou POST) para o endpoint configurado
4. Registra o resultado da requisição nos logs

## Uso e Exemplos

### Exemplo 1: Registrando uma Compra

Para registrar a compra de um lead com ID `67ce55635f424` e valor de comissão de 50:

```
GET /postback.php?subid=67ce55635f424&status=Purchase&payout=50
```

Resposta:
```
Postback for subid 67ce55635f424 with status Purchase accepted
```

### Exemplo 2: Configurando Webhooks S2S

Para configurar um webhook S2S, adicione na configuração:

```json
"s2s": [
    {
        "url": "https://meuservidor.com/webhook?subid={subid}&status={status}&payout={payout}",
        "method": "GET",
        "events": [
            "Purchase"
        ]
    }
]
```

## Logs e Monitoramento

O sistema mantém dois tipos de logs:

1. **Logs de Postback**: Registra todas as requisições de postback recebidas
   - Formato: `data_hora subid, status, payout`
   - Localização: `/pblogs/DD.MM.YY.pb.log`

2. **Logs S2S**: Registra as requisições enviadas para webhooks externos
   - Formato: `data_hora método URL status código_http`
   - Localização: `/pblogs/DD.MM.YY.pb.log`

## Conclusão

O sistema de postback é fundamental para o rastreamento de conversões e para manter o status atualizado dos leads no fluxo de marketing. Ele fornece uma interface simples para atualização de status e integração com sistemas externos, garantindo que todas as conversões sejam registradas adequadamente.

---

## Registro da Compra do Lead

A compra do lead com ID `67ce55635f424` foi registrada com sucesso no sistema com um valor de comissão de 50. O sistema atualizou o estado do lead para "Purchase" conforme solicitado, e os logs de postback foram gerados corretamente.

Detalhes da operação:
- **Lead ID**: 67ce55635f424
- **Novo Status**: Purchase
- **Valor da Comissão**: 50
- **Data/Hora**: 2025-03-13 20:52:39
- **Resultado**: Postback aceito com sucesso

O log de postback foi atualizado e pode ser encontrado em `/pblogs/13.03.25.pb.log`, confirmando a atualização bem-sucedida do status do lead.
