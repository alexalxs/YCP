# To Fix
<!-- Espaço reservado para funcionalidades que precisam ser corrigidas -->

# To Do
<!-- Espaço reservado para funcionalidades que precisam ser implementadas -->

# Test
<!-- Espaço reservado para funcionalidades em fase de teste -->

# Done

## Funções Principais

### white($use_js_checks)
- Gerencia o comportamento do tráfego "white" (permitido)
- Parâmetros:
  - `$use_js_checks`: Boolean - Indica se deve usar verificações JavaScript
- Funcionalidades:
  - Processa diferentes ações baseadas em configurações globais
  - Suporta diferentes tipos de ações: error, redirect, folder, curl
  - Permite configurações específicas por domínio
  - Gerencia cookies de referência
  - Implementa verificações JavaScript quando necessário

### black($clkrdetect)
- Gerencia o comportamento do tráfego "black" (específico)
- Parâmetros:
  - `$clkrdetect`: Parâmetro para detecção de cliques
- Funcionalidades:
  - Configura headers CORS
  - Gerencia cookies do Facebook
  - Suporta diferentes ações para pre-landing e landing pages
  - Implementa sistema de redirecionamento
  - Gerencia subIDs e tracking de cliques
  - Processa URLs e macros para redirecionamento

## Configurações Globais
- Suporte a debug com display de erros
- Gerenciamento de headers e cookies
- Sistema de AB testing integrado
- Processamento de URLs e parâmetros
- Sistema de tracking e análise 