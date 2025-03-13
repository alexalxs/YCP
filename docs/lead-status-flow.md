# Diagrama de Estado: Ciclo de Vida do Lead

Este diagrama ilustra as mudanças de estado de um lead ao longo do seu ciclo de
vida, desde a criação até os estados finais.

```mermaid
stateDiagram-v2
    [*] --> Lead: Envio do formulário<br/>send.php
    
    Lead --> Purchase: Postback de compra confirmada<br/>status=Purchase
    Lead --> Reject: Postback de rejeição<br/>status=Reject
    Lead --> Trash: Postback de descarte<br/>status=Trash
    
    Purchase --> [*]: Estado final
    Reject --> [*]: Estado final
    Trash --> [*]: Estado final
    
    note left of [*]
        Métodos de criação de Lead:
        1. Via formulário (offer1/index.html)
        2. Via create_lead.php (teste direto)
    end note
    
    note right of Lead
        Estado inicial quando o lead é criado
        - Armazenado no SleekDB
        - Contém dados do formulário
        - Exibido como "Hold" no painel
    end note
    
    note right of Purchase
        Lead convertido em compra
        - Contém valor de payout
        - Contribui para métricas de CR%
        - Pode gerar comissão
    end note
    
    note right of Reject
        Lead rejeitado
        - Cliente declinou
        - Dados inválidos
        - Fraude detectada
    end note
    
    note right of Trash
        Lead descartado
        - Dados inválidos
        - Duplicado
        - Baixa qualidade
    end note
```

## Detalhamento dos Estados do Lead

### Estado: Lead

- **Criação**: Quando um usuário preenche o formulário na página de oferta
- **Processamento**: Realizado pelo arquivo `send.php`
- **Métodos de Criação**:
  1. **Via formulário**: Preenchimento do formulário em offer1/index.html
  2. **Via teste direto**: Execução do script create_lead.php
- **Dados Armazenados**:
  - subid (identificador único)
  - time (timestamp da criação)
  - name (nome do cliente)
  - phone (telefone)
  - email (se fornecido)
  - fbp, fbclid (parâmetros de rastreamento)
  - preland, land (páginas visitadas)
- **Visualização no Painel**: Aparece como "Hold" nas estatísticas
- **Próximas Transições**: Pode ser atualizado para Purchase, Reject ou Trash

### Estado: Purchase

- **Atualização**: Via postback com `status=Purchase`
- **Processamento**: Realizado pelo arquivo `postback.php`
- **Métodos de Atualização**:
  1. **Via postback automático**: Enviado pela plataforma de afiliados
  2. **Via curl manual**: Comando curl para teste de postback
- **Dados Adicionados**:
  - status = "Purchase"
  - payout (valor da comissão)
- **Significado**: O lead foi convertido em uma compra confirmada
- **Impacto nas Estatísticas**: Aumenta a taxa de conversão (CR%)
- **Estado Final**: Não há mais transições após este estado

### Estado: Reject

- **Atualização**: Via postback com `status=Reject`
- **Processamento**: Realizado pelo arquivo `postback.php`
- **Razões Comuns**:
  - Cliente declinou a oferta
  - Problemas com pagamento
  - Dados de contato inválidos
- **Impacto nas Estatísticas**: Contabilizado separadamente nas estatísticas
- **Estado Final**: Não há mais transições após este estado

### Estado: Trash

- **Atualização**: Via postback com `status=Trash`
- **Processamento**: Realizado pelo arquivo `postback.php`
- **Razões Comuns**:
  - Lead de baixa qualidade
  - Duplicação detectada
  - Fraude ou spam
- **Impacto nas Estatísticas**: Contabilizado nas estatísticas como descartado
- **Estado Final**: Não há mais transições após este estado

## Implementação no Código

### Criação de Lead via Formulário

```php
// Em send.php - criação do lead via formulário
$name = $_POST['name'];
$phone = $_POST['phone'];
$subid = get_subid();
add_lead($subid, $name, $phone);
```

### Criação de Lead via Script de Teste

```php
// Em create_lead.php - criação do lead para teste
$subid = uniqid('test_');
$name = 'Cliente Teste Direto';
$phone = '11987654321';
$result = add_lead($subid, $name, $phone, 'Lead');
```

### Atualização de Status via Postback

```php
// Em postback.php - atualização de status
$subid = $_REQUEST['subid'];
$status = $_REQUEST['status'];
$payout = $_REQUEST['payout'];
update_lead($subid, $status, $payout);
```

Os status são definidos no arquivo `settings.json` e processados pelo
`postback.php`:

```json
"postback": {
    "lead": "Lead",
    "purchase": "Purchase",
    "reject": "Reject",
    "trash": "Trash",
    ...
}
```

A função `update_lead()` no arquivo `db.php` é responsável por realizar a
transição de estado:

```php
function update_lead($subid, $status, $payout) {
    $dataDir = __DIR__ . "/logs";
    $leadsStore = new Store("leads", $dataDir);
    $lead = $leadsStore->findOneBy([["subid", "=", $subid]]);
    
    // ... código de verificação ...
    
    $lead["status"] = $status;
    $lead["payout"] = $payout;
    $leadsStore->update($lead);
    return true;
}
```

## Método Recomendado para Testes

Para testes completos do fluxo de conversão:

1. **Gerar um Lead**:
   - Acesse `create_lead.php` para criar um lead com status "Lead"
   - Anote o subid gerado (ex: test_67d3260cb7978)

2. **Verificar Estatísticas Iniciais**:
   - Acesse `admin/statistics.php?password=12345`
   - Confira que o lead está contabilizado como "Hold"

3. **Atualizar o Status**:
   - Execute o comando curl exibido pelo script create_lead.php:
   ```bash
   curl -v "http://localhost:8000/postback.php?subid=test_67d3260cb7978&status=Purchase&payout=99.99"
   ```

4. **Verificar Estatísticas Atualizadas**:
   - Acesse novamente `admin/statistics.php?password=12345`
   - Confira que o lead agora está como "Purchase" e a receita é 99.99
