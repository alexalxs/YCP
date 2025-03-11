# To Fix ( deve alterar a funcionalidade pois esta incorreta):

# To Do ( deve criar a funcionalidade pois esta faltando):

# Test ( não deve auterar a funcionalidade pois esta em fase de teste pelo usuário):

# Done ( não deve auterar a funcionalidade pois esta correta):

## Sistema de Redirecionamento White/Black

O sistema utiliza um modelo de redirecionamento dual que permite separar o
tráfego em dois fluxos distintos:

### Modo White (Tráfego "Limpo")

- **Ação por Pasta**: Redirecionamento para pastas específicas ("branca")
- **Redirecionamento HTTP**: Suporte para redirecionamentos 303 para URLs
  específicas
- **Requisições cURL**: Capacidade de fazer requisições para URLs externas
- **Códigos de Erro**: Configuração de códigos de erro HTTP personalizados
- **Filtro de Domínio**: Opção para filtrar com base em domínios específicos
- **Verificações JavaScript**: Capacidade de verificar características do
  navegador (audiocontext, timezone) para detecção de bots

### Modo Black (Tráfego de Conversão)

- **Gestão de Prelanding**: Opções para configurar páginas de pre-landing em
  pastas específicas
- **Gestão de Landing**: Opções para configurar landing pages em pastas
  específicas ("oferta")
- **Thank You Page Customizada**: Capacidade de configurar páginas de
  agradecimento com suporte a upsell
- **Script de Conversão**: Integração com scripts para rastreamento de
  conversões
- **Substituição de JavaScript**: Opção para substituir scripts JavaScript nas
  páginas
