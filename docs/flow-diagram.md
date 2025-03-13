# Diagrama de Sequência: Fluxo de Conversão

Este diagrama ilustra o fluxo completo de conversão desde o clique inicial do
usuário até o registro final na plataforma de afiliados, mostrando todas as
mudanças de estado.

```mermaid
sequenceDiagram
    participant U as Usuário
    participant FT as Fonte de Tráfego
    participant LP as Landing Page (YCP)
    participant OF as Página de Oferta
    participant SP as send.php
    participant PB as postback.php
    participant TY as Página de Agradecimento
    participant DB as Banco de Dados SleekDB
    participant PA as Plataforma de Afiliados
    participant EST as Painel de Estatísticas

    U->>FT: Clica no anúncio
    FT->>LP: Redireciona com parâmetros<br/>(?subid=123&adn=abc)
    LP->>LP: Verifica TDS<br/>e cookies
    LP->>DB: Registra clique<br/>(blackclicks)
    LP->>OF: Redireciona para oferta<br/>mantendo parâmetros

    U->>OF: Preenche formulário<br/>e clica em "Comprar"
    OF->>SP: Envia dados do formulário<br/>(POST)
    
    SP->>SP: Verifica se há duplicatas
    SP->>DB: Cria lead<br/>status = "Lead"
    Note over DB: Estado: Lead
    SP->>TY: Redireciona para<br/>página de agradecimento
    
    TY->>TY: Mostra confirmação<br/>do pedido ao usuário
    
    Note over PA,DB: Atualização do lead para Purchase
    PA->>PB: Envia postback para atualizar status<br/>(?subid=123&status=Purchase&payout=99.99)
    PB->>DB: Atualiza status do lead<br/>status = "Purchase"
    Note over DB: Estado: Purchase
    PB->>PA: Envia confirmação<br/>de atualização
    
    EST->>DB: Consulta leads<br/>para estatísticas
    DB->>EST: Retorna dados<br/>de conversão
    EST->>U: Exibe estatísticas<br/>de conversão por status
```

## Descrição do Fluxo

1. **Aquisição do Usuário**:
   - Usuário clica em um anúncio na Fonte de Tráfego
   - É redirecionado para a Landing Page com parâmetros de rastreamento (subid)

2. **Navegação na Oferta**:
   - A Landing Page verifica o TDS e registra o clique no banco de dados
   - Usuário é redirecionado para a página de oferta específica

3. **Conversão Inicial (Lead)**:
   - Usuário preenche o formulário e envia
   - send.php processa os dados e cria um lead no banco com status "Lead"
   - Usuário é redirecionado para a página de agradecimento

4. **Conversão Final (Purchase)**:
   - A Plataforma de Afiliados envia um postback para atualizar o status
   - postback.php atualiza o status do lead para "Purchase"
   - O sistema confirma a atualização para a Plataforma de Afiliados

5. **Visualização de Estatísticas**:
   - O Painel de Estatísticas consulta o banco de dados
   - As conversões são exibidas classificadas por status (Lead, Purchase,
     Reject, Trash)

Este diagrama representa o caso de sucesso onde um lead é criado e
posteriormente convertido em Purchase.
