```
                            Yellow Cloaker  
    _            __     __  _ _             __          __  _     
   | |           \ \   / / | | |            \ \        / / | |    
   | |__  _   _   \ \_/ /__| | | _____      _\ \  /\  / /__| |__  
   | '_ \| | | |   \   / _ \ | |/ _ \ \ /\ / /\ \/  \/ / _ \ '_ \ 
   | |_) | |_| |    | |  __/ | | (_) \ V  V /  \  /\  /  __/ |_) |
   |_.__/ \__, |    |_|\___|_|_|\___/ \_/\_/    \/  \/ \___|_.__/ 
           __/ |                                                  
          |___/             https://yellowweb.top                 

Se você gosta deste script, POR FAVOR FAÇA UMA DOAÇÃO!  
USDT TRC20: TKeNEVndhPSKXuYmpEwF4fVtWUvfCnWmra
Bitcoin: bc1qqv99jasckntqnk0pkjnrjtpwu0yurm0qd0gnqv  
Ethereum: 0xBC118D3FDE78eE393A154C29A4545c575506ad6B
```

# Yellow Cloaker por [Yellow Web](https://yellowweb.top)

Atenção: este manual está desatualizado! Agora todas as configurações são feitas
através da interface: _/admin?password=12345_ **Use PHP versão 7.2 ou superior e
crie certificados HTTPS para todos os seus domínios!**

# Suporte

Se você quer que este projeto continue se desenvolvendo,
[**apoie o autor com algumas moedas**!](https://t.me/yellowwebdonate_bot)

# Descrição

Script de cloaking modificado para marketing de afiliados, originalmente
encontrado no [Black Hat World](http://blackhatworld.com).

# Material de Referência

- [ÚLTIMA transmissão onde TODA a interface gráfica do cloaker é explicada](https://youtu.be/-ikmpq-L8ZE)
- [Transmissão onde o cloaker é explicado em detalhes com todas as funções](https://www.youtube.com/watch?v=XMua15r2dwg&feature=youtu.be)
- [Vídeo com visão geral dos novos recursos aqui.](https://www.youtube.com/watch?v=x-Z2Y4lEOc0&t=656s)
- [Descrição da configuração das primeiras versões aqui!](https://yellowweb.top/%d0%ba%d0%bb%d0%be%d0%b0%d0%ba%d0%b8%d0%bd%d0%b3-%d0%b4%d0%bb%d1%8f-%d0%b1%d0%b5%d0%b4%d0%bd%d0%be%d0%b3%d0%be-%d0%bd%d0%be-%d1%83%d0%bc%d0%bd%d0%be%d0%b3%d0%be-%d0%b0%d1%80%d0%b1%d0%b8%d1%82%d1%80/)

# Instalação

Baixe a última versão de todos os arquivos deste repositório e faça o upload
para seu servidor. Seu servidor deve permitir a execução de scripts PHP e você
DEVE criar um certificado HTTPS para seu domínio. **Sem HTTPS o cloaker não
funcionará corretamente!** Posso definitivamente
[recomendar o Beget Hosting para o cloaker](https://yellowweb.top/beget). É
barato e conveniente.

Se você tem páginas de pré-landing ou landing pages locais, crie uma pasta para
cada uma delas na pasta raiz do cloaker e copie todos os arquivos
correspondentes. _Por exemplo:_ Se você tem 2 pré-landings e 2 landings, crie 2
pastas para pré-landings: p1 e p2. E 2 pastas para landings: land1, land2.

# Configuração

Atualmente, o cloaker não possui interface para as configurações. Então, basta
abrir o arquivo settings.php em qualquer editor de texto. Recomendo o Notepad++
para isso, pois ele tem destaque de sintaxe PHP e será mais fácil de ler e
editar.

## Configuração da Página Branca (White Page)

A Página Branca é uma página mostrada ao visitante que não passa por nenhum dos
filtros do cloaker. Ou seja, é para visitantes que não queremos.

Primeiro, você precisa decidir que tipo de ação de página branca quer usar. O
cloaker pode usar páginas brancas locais, pode mostrar qualquer outro site como
página branca (sem redirecionamentos), pode redirecionar o tráfego branco para
qualquer site e também pode mostrar um erro para esses visitantes.

Quando decidir, altere o valor de **$white_action** para um dos seguintes:

### site

Para páginas brancas locais. Você precisa criar uma pasta no diretório raiz do
cloaker, por exemplo: _white_ e copiar todos os arquivos da sua página branca
para lá. Depois escreva o nome da pasta no valor **$white_folder_name**.

### redirect

Escolha isso se quiser redirecionar todo o tráfego branco. Apenas insira a URL
completa do site em
**$white_redirect_url** e também escolha um tipo de redirecionamento. Pode ser 301, 302, 303 ou 307. Pesquise a diferença se precisar. Digite o valor em **$white_redirect_type**.

### curl

Use isso se quiser carregar o conteúdo de qualquer outro site em seu domínio sem
redirecionamentos. Digite a URL completa do site em **$white_curl_url**.

### error

Você pode retornar qualquer tipo de erro HTTP para todo o tráfego branco. Por
exemplo: _404_. Apenas digite o código de erro em **$white_error_code**.

## Páginas Brancas Específicas por Domínio

Se você tem MÚLTIPLOS domínios (ou subdomínios) apontando para seu servidor e
executa tráfego para todos eles, você pode escolher usar diferentes ações
brancas para diferentes domínios. Para fazer isso, primeiro mude
**$white_use_domain_specific** para _true_.

Depois preencha o array **$white_domain_specific**. O formato é assim:
`"seu.dominio" => "acaobranca:valor"` Um exemplo é fornecido no arquivo
settings.php padrão.

## Configuração da Página de Monetização

A página de monetização (chamada de Black page aqui) pode ser uma das seguintes:

- página de destino local (landing pages)
- pré-landing(s) local(is) + landing(s) local(is)
- pré-landing(s) local(is) + redirecionamento para a landing da rede de
  afiliados
- redirecionamento

Vamos explorar cada uma dessas configurações.

### Landing page(s) local(is)

Você pode usar uma ou várias landing pages se precisar. O tráfego será
distribuído proporcionalmente. Por exemplo, 50-50 para 2 landings. Cada landing
deve estar em uma pasta separada. Faça
**$black_action = *'site'*** e coloque o nome da pasta em **$black_land_folder_name**.
No caso de múltiplas landings, use vírgula como separador. Por exemplo:
`$black_land_folder_name = 'land1,land2';` _Nota:_ certifique-se de que não tem
nada em **$black_preland_folder_name**. Deve ser:
`$black_preland_folder_name = ''; `

### Pré-landing(s) local(is) + landing(s) local(is)

Faça tudo igual à descrição para **Landing page local** mas também preencha o
**$black_preland_folder_name**. Por exemplo, para duas pré-landings:
`$black_preland_folder_name = 'p1,p2';`

### Pré-landing(s) local(is) + redirecionamento

Preencha o **$black_preland_folder_name**. Por exemplo, para duas pré-landings:
`$black_preland_folder_name = 'p1,p2';` Depois mude
**$black_land_use_url** para *true*. Último passo: coloque a URL completa de redirecionamento em **$black_land_url**

### Redirecionamento

Se você só quer redirecionar todo o seu tráfego black, então use
**$black_action = *'redirect'*** e coloque a URL completa do site para onde você quer redirecionar as pessoas em **$black_redirect_url**.
Também escolha um tipo de redirecionamento. Pode ser 301, 302, 303 ou 307.
Pesquise a diferença se precisar. Digite o valor em **$black_redirect_type**.

### Configurando o script de conversão da landing local

Cada landing page tem a capacidade de enviar leads para sua rede de afiliados. E
cada rede de afiliados que fornece essas landings tem seu próprio script e
mecânica para enviar essas informações. Por padrão, o cloaker procurará o
arquivo _order.php_, que deve estar localizado na pasta da landing. Mas se seu
script tem um nome diferente, então você deve renomear o valor de
**$black_land_conversion_script**. Se seu script está em alguma pasta, coloque o nome da pasta antes do nome do script assim:
`$black_land_conversion_script='pasta/conversao.php';`Depois de configurar isso, envie um lead de teste para sua rede de afiliados. Se você não consegue ver o lead nas estatísticas da sua rede, então abra seu script de conversão e procure por estas linhas:`exit();`
Remova ou comente todas elas. Depois envie um lead de teste novamente.

### Configurando a página "Obrigado"

A página Obrigado é uma página para onde o visitante é redirecionado depois de
preencher o formulário de lead na sua landing BLACK OU na sua página branca (se
você tiver uma lá). O conteúdo da página Obrigado é carregado da pasta
_thankyou_ do cloaker. Ela tem vários arquivos html lá, nomeados com o código de
idioma de 2 símbolos. Coloque o nome do seu idioma requerido em
**$thankyou_page_language**.

Se não há página Obrigado para seu idioma - crie uma! É tão fácil quanto
carregar por exemplo _EN.html_ no seu navegador Chrome, traduzir usando o Google
Translate integrado e depois salvar usando seu código de idioma. Por exemplo:
_PT.html_. **Atenção**: certifique-se de que duas macros: _{NAME}_ e _{PHONE}_
não foram traduzidas pelo Google. Se foram, apenas mude-as de volta.

Se você quer usar sua própria página Obrigado - apenas renomeie-a usando o mesmo
código de idioma de 2 símbolos para o idioma requerido e coloque todos os seus
arquivos na pasta _thankyou_.

#### Coletando emails na página "Obrigado"

A página Obrigado padrão tem um formulário de coleta de email embutido. Se você
não precisa dele - apenas delete-o no código. Mas se precisa, você precisa criar
mais uma página: aquela para onde o visitante será redirecionado DEPOIS de
enviar o formulário de email. Ela deve ser chamada usando o mesmo código de
idioma de 2 símbolos+email no final. Por exemplo: _PTemail.html_.

## Configuração de Pixels

Você pode adicionar vários pixels em suas pré-landings e landings. A lista
completa inclui:

- Yandex Metrika
- Google Tag Manager
- Facebook Pixel

### Yandex Metrika

Para adicionar o script do Yandex Metrika às suas pré-landings e landings,
apenas preencha seu id do Yandex Metrika. Coloque-o em **$ya_id**.

### Google Tag Manager

Para adicionar o script do Google Tag Manager às suas pré-landings e landings,
apenas preencha seu id GTM. Coloque-o em **$gtm_id**.

### Facebook Pixel

O id do Facebook Pixel é obtido do link que você coloca na sua fonte de tráfego.
Deve estar no formato _px=1234567890_. Por exemplo:
`https://seu.dominio?px=5499284990` Se a URL tem este parâmetro _px_, então o
código javascript completo do Facebook Pixel será adicionado à página Obrigado.
Você pode definir o evento do Facebook Pixel na variável
**$fb_thankyou_event**. Por padrão é *Lead* mas você pode mudá-lo para *Purchase* ou qualquer coisa que você precise.
Você também pode usar o evento *PageView* do pixel. Para fazer isso, mude **$fb_use_pageview**
para _true_. Se você fizer isso, então o código do pixel será adicionado a todas
as suas pré-landings e landings locais e elas enviarão o evento _PageView_ para
cada visitante ao Facebook. Use o plugin Facebook Pixel Helper para Google
Chrome para verificar se os eventos do pixel disparam corretamente!

## Configuração dos Filtros do Cloaker

O cloaker pode filtrar tráfego baseado em:

- Base de dados de IP integrada
- Sistema Operacional do visitante
- País do visitante
- User Agent do visitante
- ISP do visitante
- Referenciador do visitante
- Qualquer token na URL

_Nota:_ vírgula deve ser usada em todo lugar onde valores múltiplos são
necessários. Primeiro coloque todos os SOs que devem ser permitidos para ver a
página black em **$os_white**. A lista completa é:

- Android
- iOS
- Windows
- Linux
- OS X
- e alguns outros não significativos

Escolha qualquer um que você precise. Depois coloque todos os códigos de país
que são permitidos em **$country_white**. Por exemplo: _BR,PT,US,ES_.

Agora livre-se de todos os Provedores de Serviço de Internet que você não
precisa. Coloque-os em **$isp_black**. Por exemplo: _google,facebook,yandex_. Se
você quer proteger suas landings de serviços de Espionagem use _amazon,azure_ e
outros provedores de nuvem aqui.

Coloque todos os User Agents desnecessários em **$ua_black**. Por exemplo:
_facebook,Facebot,curl,gce-spider_

Coloque todas as palavras que podem ser encontradas na URL que sinalizam que
este visitante deve ver a página branca em **$tokens_black** ou deixe vazio.

Se você tem quaisquer endereços IP adicionais que você quer se livrar -
coloque-os em **$ip_black**.

E por último mas não menos importante: se você quer bloquear visitantes
_diretos_ de ver sua página black, então mude **$block_without_referer** para
_true_. **Atenção**: alguns SOs e navegadores não passam o referenciador
corretamente, então teste isso primeiro em uma pequena quantidade de tráfego ou
você perderá dinheiro.

## Configuração de Distribuição de Tráfego

Você pode temporariamente desabilitar todos os seus filtros e enviar todo
tráfego para a página branca. Por exemplo, você pode usar isso para moderação.
Para fazer isso, mude **$full_cloak_on** para *true*.
Você também pode desabilitar os filtros e sempre mostrar a página black. Por exemplo, para propósitos de teste. Para fazer isso mude **$disable_tds**
para _true_. Você pode salvar o fluxo do usuário (as pré-landings e landings que
serão mostradas ao visitante) então ele(a) sempre verá as mesmas páginas quando
visitar o site pela segunda vez ou mesmo apenas atualizar a página. Para fazer
isso, mude **$save_user_flow** para _true_.

## Configuração de Estatísticas e Postback

Suas estatísticas são protegidas com uma senha, para definí-la, por favor
preencha a variável **$log_password**.
Se você nomeia seus criativos adequadamente e passa seus nomes da fonte de tráfego, você pode ver o número de cliques para cada criativo nas Estatísticas. Para fazer isso, por favor coloque o nome do parâmetro no qual você passa o nome do criativo na variável **$creative_sub_name**.
Por exemplo, se seu link se parece com isso:
`https://seu.dominio?meucriativo=otimocriativo` então você precisa fazer isso
assim: `$creative_sub_name = 'meucriativo';`

### Configuração de postback

O cloaker é capaz de receber postbacks da sua rede de afiliados. Para fazer
isso, primeiro você precisa passar o id único do visitante (chamado subid aqui)
para sua rede. Subid é criado para cada visitante e é armazenado em um cookie.
Você deve perguntar ao seu gerente de afiliados como você deve passar este id
(eles o conhecem como "clickid") e qual sub-parâmetro você deve usar. Geralmente
é feito usando sub-parâmetros como _sub1_ ou _subacc_. Vamos ficar com _sub1_
para este exemplo. Então, devemos editar o array
**$sub_ids**, a parte que tem *subid* no lado esquerdo para ficar assim:
`$sub_ids = array("subid"=> "sub1", .....);` Desta forma dizemos ao cloaker para
pegar o valor de _subid_ e adicioná-lo a todos os formulários na landing na
forma de _sub1_ (ou adicioná-lo ao seu link de redirecionamento, se você não tem
landing local). Então se o _subid_ era _12uion34i2_ teremos:

- no caso de landing local `<input type="hidden" name="sub1" value="12uion34i2"`
- no caso de redirecionamento `http://redirect.link?sub1=12uion34i2`

Agora precisamos dizer à rede de afiliados onde enviar a informação de postback.
O cloaker tem o arquivo _postback.php_ em sua pasta raiz. É o arquivo que recebe
e processa postbacks. Precisamos receber 2 parâmetros da rede de afiliados:
_subid_ e status do lead. Usando estas duas coisas podemos mudar o status do
lead em nossos logs e mostrar esta mudança nas estatísticas. Procure na ajuda ou
pergunte ao seu gerente: quais macros sua rede usa para enviar _status_,
geralmente é chamado o mesmo: _{status}_. Então, voltando ao nosso exemplo:
enviamos _subid_ em _sub1_ então as macros para receber de volta nosso _subid_
será _{sub1}_. Vamos criar uma URL de postback completa. Você deve colocar esta
URL no campo Postback da sua Rede de Afiliados. Por exemplo:
`https://seu.dominio/postback.php?subid={sub1}&status={status}` Agora, pergunte
ao seu gerente de afiliados ou procure na seção de ajuda deles, quais são os
status que eles nos enviam no postback. Geralmente são:

- Lead
- Purchase
- Reject
- Trash

Se sua rede de afiliados usa outros status então mude os valores destas
variáveis de acordo:

- **$lead_status_name**
- **$purchase_status_name**
- **$reject_status_name**
- **$trash_status_name**

Depois de configurar isso envie um lead de teste e observe na página Leads como
o status muda para _Trash_ depois de um tempo.

## Configuração de Scripts Adicionais

### Desabilitar Botão Voltar

Você pode desabilitar o botão voltar no navegador do visitante, então ele(a) não
pode deixar sua página. Para fazer isso mude **$$disable_back_button** para
_true_.

### Substituir Botão Voltar

Você pode substituir a URL do botão voltar no navegador do visitante. Então
depois que ele(a) clicar nele, ele(a) será redirecionado para algum outro lugar,
por exemplo para outra oferta. Para fazer isso mude
**$replace_back_button** para *true* e coloque a URL que você quer em **$replace_back_address**.
**Atenção:** Não use este script com o script **Desabilitar Botão Voltar**!!!

### Desabilitar Seleção de Texto, Ctrl+S e Menu de Contexto

Você pode desabilitar a habilidade de selecionar texto em suas pré-landings e
landings, desabilitar a habilidade de salvar a página usando as teclas Ctrl+S e
também desabilitar o menu de contexto do navegador. Para fazer isso apenas mude
**$disable_text_copy** para _true_.

### Substituindo Pré-landing

Você pode fazer o cloaker abrir a landing page em uma aba separada do navegador
e então redirecionar a aba com a pré-landing para outra URL. Depois que o
usuário fechar sua aba de landing page ele(a) verá a aba com esta URL. Use isso
para mostrar outra oferta ao usuário. Para fazer isso mude
**$replace_prelanding** para *true* e coloque sua URL em **$replace_prelanding_address**.

### Máscaras de Telefone

Você pode dizer ao cloaker para usar máscaras para o campo de telefone em suas
landings locais. Quando você faz isso, o visitante não poderá adicionar nenhuma
letra no campo de telefone, apenas números. A máscara define contagem de números
e delimitadores. Para habilitar máscaras apenas mude
**$black_land_use_phone_mask** para *true* e edite sua máscara em **$black_land_phone_mask**.

# Verificação

Adicione seu próprio país aos filtros do cloaker para poder ver a página black.
Então passe por todos os componentes do funil. Envie um lead de teste, verifique
se ele chegou à sua rede de afiliados.

# Executando tráfego e Estatísticas

Depois que você começou a executar tráfego você pode monitorá-lo e também olhar
as estatísticas. Para fazer isso apenas vá para um link como este:
`https://seu.dominio/logs?password=suasenha` onde _suasenha_ é um valor de
**$log_password** do arquivo settings.php.

# Integração Javascript

Você pode conectar este cloaker a qualquer site ou construtor de sites que
permite adicionar Javascript. Por exemplo: _GitHub, Wix, Shopify_ e assim por
diante. Quando você faz isso você executa tráfego para o construtor de sites e
depois que o visitante vem para este site um pequeno script verifica se ele(a)
tem permissão para ver a página black. Se ele(a) tem, então 2 coisas podem
acontecer:

- Um redirecionamento para sua página black
- O conteúdo do construtor de sites é substituído pela página black

## Redirecionamento

Apenas adicione este script ao seu construtor de sites:
`<script src="https://seu.dominio/js/indexr.php"></script>`

## Substituição de conteúdo

Apenas adicione este script ao seu construtor de sites:
`<script src="https://seu.dominio/js"></script>` Não use este método se você tem
apenas landings sem pré-landings!

# Detalhes Técnicos

## Componentes usados

Este cloaker usa:

- Bancos de dados MaxMind para detecção de ISP, País e Cidade
- Ranges de IP de Bot de várias fontes coletadas por toda a Internet em formato
  CIDR
- Sinergi BrowserDetector para (surpresa!) detecção de navegador
- IP Utils do Symphony para verificar se o endereço IP está em um intervalo
  selecionado
- Ícones de https://www.flaticon.com/free-icons/question

## Fluxo de tráfego

Depois que o visitante passa pelos filtros do cloaker ele geralmente vê a
pré-landing (se você tiver uma). Na pré-landing todos os links são substituídos
pelo link para o script _landing.php_. Depois que o visitante clica no link, o
script _landing.php_ obtém o conteúdo da landing, substitui a ação de todos os
formulários para _send.php_, adiciona todos os scripts adicionais e mostra o
conteúdo ao visitante. Quando o visitante preenche o formulário e envia
_send.php_ chama o script de envio original e então remove todos os
redirecionamentos dele. Depois disso _send.php_ redireciona para o
_thankyou.php_ que mostra a página obrigado como descrito nas seções acima.
