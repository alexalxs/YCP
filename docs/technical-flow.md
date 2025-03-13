# Diagrama Técnico: Implementação e Estrutura de Dados

Este diagrama detalha a implementação técnica e a estrutura de dados no fluxo de
conversão, com foco nas operações de banco de dados e nos formatos de dados
trocados.

```mermaid
sequenceDiagram
    participant OF as Página de Oferta (HTML/JS)
    participant SP as send.php
    participant PB as postback.php
    participant DB as SleekDB
    participant LOG as pblogs (JSON logs)
    participant PA as Plataforma de Afiliados API
    participant EST as statistics.php

    OF->>SP: POST /send.php<br/>{name, phone, email, oferta, product, price}
    
    SP->>SP: Verifica cookies<br/>para duplicatas
    
    SP->>DB: add_lead(subid, name, phone)<br/>{status: "Lead"}
    Note over DB: Lead Store (JSON)<br/>{<br/>  "subid": "123abc",<br/>  "time": 1741890052,<br/>  "name": "Teste Usuario",<br/>  "phone": "11987654321",<br/>  "status": "Lead",<br/>  "fbp": "fb.1.123",<br/>  "preland": "preland1",<br/>  "land": "offer1"<br/>}
    
    SP->>+PA: POST /conversion.php<br/>{subid, name, phone}
    PA-->>-SP: HTTP 200 OK

    Note over PB,PA: Atualização posterior via postback
    
    PA->>+PB: GET /postback.php?subid=123abc&status=Purchase&payout=99.99
    
    PB->>LOG: add_postback_log()<br/>Grava em /pblogs/{data}.pb.log
    Note over LOG: Arquivo de log (texto)<br/>2025-03-13 18:20:52 123abc, Purchase, 99.99
    
    PB->>DB: update_lead(subid, "Purchase", payout)
    Note over DB: Lead Store (atualizado)<br/>{<br/>  "subid": "123abc",<br/>  "time": 1741890052,<br/>  "name": "Teste Usuario",<br/>  "phone": "11987654321",<br/>  "status": "Purchase",<br/>  "payout": "99.99",<br/>  "fbp": "fb.1.123",<br/>  "preland": "preland1",<br/>  "land": "offer1"<br/>}
    
    PB->>PA: Envia S2S postbacks configurados<br/>para plataformas externas
    Note over PA: Configuração S2S em settings.json<br/>{<br/>  "url": "https://tracker.com/postback?sid={subid}",<br/>  "method": "GET",<br/>  "events": ["Lead", "Purchase"]<br/>}
    
    PB-->>-PA: "Postback for subid 123abc accepted"
    
    EST->>DB: Consulta statistics<br/>(Query Builder)
    DB-->>EST: Resultados agrupados<br/>por status
    Note over EST: Estatísticas em HTML<br/>Total Leads: 10<br/>Conversions: 5<br/>Hold: 2<br/>Reject: 2<br/>Trash: 1<br/>CR%: 50%
```

## Detalhamento Técnico

### Estruturas de Dados

1. **Lead no SleekDB**:
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

2. **Configuração de Postback (settings.json)**:
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

3. **Log de Postback**:
   ```
   2025-03-13 18:20:52 123abc, Purchase, 99.99
   2025-03-13 18:21:14 GET https://tracker.com/postback?sid=123abc&status=Purchase 200
   ```

### Implementação dos Principais Componentes

1. **send.php**: Processa o formulário de pedido, verifica duplicatas via
   cookies, cria leads no banco de dados e redireciona para a página de
   agradecimento.

2. **postback.php**: Recebe atualizações de status de leads, atualiza o banco de
   dados, registra logs e envia postbacks S2S configurados para plataformas
   externas.

3. **SleekDB**: Banco de dados JSON que armazena leads, cliques e outras
   informações em arquivos estruturados.

4. **statistics.php**: Consulta o banco de dados para extrair métricas de
   conversão e exibe as estatísticas no painel administrativo.
