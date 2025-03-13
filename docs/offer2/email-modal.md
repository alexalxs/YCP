# Modal de Captura de Email

## To Fix (deve alterar a funcionalidade pois está incorreta):

## To Do (deve criar a funcionalidade pois está faltando):
Adicine captura de nome;

## Test (não deve alterar a funcionalidade pois está em fase de teste pelo usuário):

## Done (não deve alterar a funcionalidade pois está correta):

### Estrutura do Modal

- **HTML**: Modal com formulário para captura de email implementado no arquivo
  `offer2/index.html`
- **CSS**: Estilos específicos para o modal incluindo animações e estados de
  hover
- **JavaScript**: Funcionalidades interativas do modal

### Funcionalidades

- **Exibição do Modal**: Acionado ao clicar no botão CTA "CONHECER O MÉTODO
  AGORA"
- **Validação de Email**: Verifica se o email inserido pelo usuário é válido
  antes de enviar o formulário
- **Fechamento do Modal**: Pode ser fechado clicando no "X" ou clicando fora da
  área do modal
- **Mensagem de Sucesso**: Exibe uma mensagem de sucesso após o email ser
  registrado
- **Redirecionamento**: Redireciona o usuário para
  `https://dekoola.com/ch/hack/` após envio bem-sucedido
- **Preservação de Parâmetros**: Mantém todos os parâmetros URL originais no
  redirecionamento

### Fluxo de Operação

1. Usuário clica no botão CTA na página principal
2. Modal é exibido com formulário de captura de email
3. Usuário insere email e submete o formulário
4. Sistema valida o email no frontend
5. Se válido, envia para o backend via fetch API
6. Exibe mensagem de sucesso
7. Redireciona com todos os parâmetros originais da URL
