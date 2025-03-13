# Sistema de Redirecionamento com Preservação de Parâmetros

## To Fix (deve alterar a funcionalidade pois está incorreta):

## To Do (deve criar a funcionalidade pois está faltando):
- Adicionar: Após captura do email é necessário registrar localmente o evento de lead salvando os dados;
## Test (não deve alterar a funcionalidade pois está em fase de teste pelo usuário):

## Done (não deve alterar a funcionalidade pois está correta):

### Estrutura do Sistema

- **Implementação**: Sistema JavaScript implementado no arquivo;
- **Propósito**: Redirecionar usuários após envio de email preservando
  parâmetros UTM
- **Funções Principais**: `getUrlParams()`, `buildRedirectUrl()` e parte do
  `handleFormSubmit()`

### Funcionalidades

- **Captura de Parâmetros**: Extrai todos os parâmetros da URL atual
- **Construção de URL**: Monta a URL de destino mantendo todos os parâmetros
  originais
- **Redirecionamento Temporizado**: Redireciona após breve delay para exibir
  mensagem de sucesso
- **Suporte a UTM**: Preserva especificamente parâmetros de rastreamento UTM e
  outros
- **Destino Configurável**: Redirecionamento para `https://dekoola.com/ch/hack/`
  com todos os parâmetros

### Fluxo de Operação

1. Usuário submete o formulário de email
2. Frontend processa a submissão e envia para o backend
3. Após confirmação de sucesso, exibe mensagem de confirmação
4. Sistema captura todos os parâmetros da URL atual
5. Constrói a URL de destino incluindo todos os parâmetros
6. Após 2 segundos, redireciona o usuário para a URL construída

### Parâmetros Preservados

- Todos os parâmetros são preservados, incluindo mas não limitado a:
  - **key**: Parâmetro usado para identificação da chave de acesso
  - **offer**: Parâmetro que identifica qual oferta está sendo exibida
  - **UTM Parameters**: utm_source, utm_medium, utm_campaign, utm_term,
    utm_content
  - **Quaisquer outros parâmetros**: Todos os parâmetros adicionais são mantidos
