# To Fix ( deve alterar a funcionalidade pois esta incorreta):

# To Do ( deve criar a funcionalidade pois esta faltando):

# Test ( não deve auterar a funcionalidade pois esta em fase de teste pelo usuário):

# Done ( não deve auterar a funcionalidade pois esta correta):

## Sistema de Distribuição de Tráfego (TDS)

O componente TDS é responsável pelo controle principal de distribuição de
tráfego com as seguintes funcionalidades:

### Modos de Operação

- **Modo "on/off"**: Define se o sistema de filtragem está ativo ou desativado
- **Salvamento de Fluxo de Usuário**: Capacidade de registrar e armazenar o
  caminho percorrido pelos visitantes

### Filtros Permitidos

- **Países**: Filtragem baseada na localização geográfica do visitante
- **Sistemas Operacionais**: Suporte para filtragem por Android, iOS, Windows,
  OS X e outros
- **Parâmetros de URL**: Capacidade de filtrar acessos baseados em parâmetros
  específicos na URL
- **Idiomas**: Filtragem baseada nas configurações de idioma do navegador

### Filtros de Bloqueio

- **IPs**: Bloqueio baseado em endereços IP específicos (com suporte a formato
  CIDR)
- **Tokens**: Bloqueio baseado em tokens específicos
- **User Agents**: Bloqueio de user agents conhecidos como bots (Facebook,
  Yandex, etc.)
- **ISPs**: Bloqueio de provedores específicos (Google, Facebook, Amazon, etc.)
- **Referenciadores**: Opções para bloquear referenciadores vazios ou contendo
  palavras específicas
- **VPN/Tor**: Capacidade de detectar e bloquear acessos via VPN ou rede Tor
