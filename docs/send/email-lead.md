# Processamento de Leads por Email

## To Fix (deve alterar a funcionalidade pois está incorreta):

## To Do (deve criar a funcionalidade pois está faltando):

## Test (não deve alterar a funcionalidade pois está em fase de teste pelo usuário):

## Done (não deve alterar a funcionalidade pois está correta):

### Estrutura do Sistema

- **Endpoint**: Implementado no arquivo `send.php`
- **Funcionalidade**: Processa solicitações de captura de email enviadas pelo
  frontend
- **Método**: Aceita solicitações POST com o parâmetro `action=email`
- **Integração**: Atua como proxy para enviar dados para o webhook do Autonami

### Funcionalidades

- **Validação de Email**: Verifica se o email recebido é válido utilizando
  `filter_var()`
- **Gestão de SubID**: Usa o SubID existente ou gera um novo se necessário
- **Armazenamento Local**: Registra o email no banco de dados usando a função
  `add_email()`
- **Proxy para Webhook**: Envia os dados para o webhook externo do Autonami via
  cURL
- **CORS Habilitado**: Configurado para permitir solicitações de diferentes
  origens
- **Logs Detalhados**: Registra informações de depuração quando DEBUG_LOG está
  habilitado

### Fluxo de Operação

1. Frontend envia solicitação POST para `/send.php` com action=email
2. Sistema valida a presença e formato do email
3. Verifica o SubID atual ou gera um novo
4. Registra o email no banco de dados local
5. Envia os dados para o webhook externo usando cURL
6. Retorna resposta JSON com status de sucesso e detalhes do webhook
7. Frontend processa a resposta e redireciona o usuário

### Parâmetros Aceitos

- **email**: Endereço de email do usuário (obrigatório)
- **subid**: Identificador único do usuário (opcional)
- **[parâmetros adicionais]**: Quaisquer parâmetros adicionais são repassados
  para o webhook
