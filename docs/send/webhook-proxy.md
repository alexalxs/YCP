# Sistema de Proxy para Webhook Externo

## To Fix (deve alterar a funcionalidade pois está incorreta):

## To Do (deve criar a funcionalidade pois está faltando):

## Test (não deve alterar a funcionalidade pois está em fase de teste pelo usuário):

## Done (não deve alterar a funcionalidade pois está correta):

### Estrutura do Sistema

- **Implementação**: Mecanismo de proxy implementado no arquivo `send.php`
- **Propósito**: Contornar limitações de CORS ao enviar dados para webhooks
  externos
- **Método**: Recebe dados do frontend e os encaminha para o endpoint externo
  via servidor

### Funcionalidades

- **Bypass de CORS**: Contorna as restrições de Same-Origin Policy dos
  navegadores
- **Processamento de Dados**: Recebe os dados via POST e os reenvia para webhook
  externo
- **Headers Personalizados**: Configurado para enviar cabeçalhos corretos para o
  webhook
- **Retorno de Status**: Retorna o status da requisição ao webhook para o
  frontend
- **Logs de Depuração**: Registra detalhes da requisição e resposta para fins de
  depuração

### Fluxo de Operação

1. Frontend envia dados para o servidor local via fetch API
2. Servidor recebe e processa os dados
3. Servidor cria uma nova requisição cURL para o webhook externo
4. O cURL é configurado com os dados recebidos e cabeçalhos adequados
5. A resposta do webhook é capturada e registrada
6. O status e detalhes da operação são retornados ao frontend

### Integração Específica - Autonami

- **Endpoint**:
  `https://dekoola.com/wp-json/autonami/v1/webhook/?bwfan_autonami_webhook_id=10&bwfan_autonami_webhook_key=92c39df617252d128219dba772cff29a`
- **Método HTTP**: POST
- **Formato dos Dados**: application/x-www-form-urlencoded
- **Dados Enviados**: Email do usuário e todos os parâmetros da URL original

### Segurança e Considerações

- O sistema não armazena dados sensíveis além do necessário
- Utiliza conexão HTTPS para comunicação com o webhook externo
- Validação dos dados recebidos antes de encaminhá-los
