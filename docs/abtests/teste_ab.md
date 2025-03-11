# To Fix ( deve alterar a funcionalidade pois esta incorreta):

# To Do ( deve criar a funcionalidade pois esta faltando):

# Test ( não deve auterar a funcionalidade pois esta em fase de teste pelo usuário):

# Done ( não deve auterar a funcionalidade pois esta correta):

## Sistema de Bloqueio de IPs

O sistema de bloqueio de IPs permite filtrar acessos com base em endereços IP
específicos através das seguintes configurações:

### Configuração de IPs Bloqueados

- **Arquivo de IPs**: No arquivo settings.json, a configuração é definida em
  `tds.filters.blocked.ips.filename`
- **Formato CIDR**: A opção `tds.filters.blocked.ips.cidrformat` define se o
  arquivo contém IPs em formato CIDR ou simples
- **Localização do Arquivo**: Os arquivos de lista de IPs bloqueados devem ser
  colocados na pasta `bases/`

### Tipos de Bloqueios de IP

- **Lista Base**: O sistema usa automaticamente o arquivo `bases/bots.txt` para
  bloquear IPs conhecidos de bots
- **Lista Personalizada**: Você pode definir sua própria lista de IPs em um
  arquivo separado
- **Bloqueio de VPN/Tor**: A opção `tds.filters.blocked.vpntor` permite bloquear
  acessos via VPN ou rede Tor

### Como Configurar

1. Crie um arquivo de texto com a lista de IPs a serem bloqueados
2. Coloque esse arquivo na pasta `bases/`
3. No arquivo settings.json, em `tds.filters.blocked.ips.filename`, insira o
   nome do arquivo criado
4. Defina `tds.filters.blocked.ips.cidrformat` como `true` se sua lista usar
   formato CIDR, ou `false` para formato simples

## Sistema de Teste A/B

O sistema de teste A/B permite comparar a eficácia de diferentes landing pages
ou prelanding pages dividindo o tráfego entre elas.

### Funcionamento Básico

- O sistema seleciona aleatoriamente uma das opções configuradas para cada novo
  visitante
- A seleção é mantida para visitas subsequentes do mesmo usuário através de
  cookies (se ativado)
- Suporte para testar tanto prelanding pages quanto landing pages

### Componentes Principais

- **Seleção Aleatória**: Divisão automática do tráfego entre as variantes
  configuradas
- **Persistência**: Opção para manter a mesma variante para o mesmo usuário
  usando cookies
- **Suporte a GEO**: Capacidade de direcionar para variantes específicas por
  país

### Como Configurar Testes A/B

1. **Para Landing Pages**:
   - No arquivo settings.json, adicione múltiplas opções em
     `black.landing.folder.names`
   - Por exemplo: `"names": ["oferta1", "oferta2", "oferta3"]`
   
   - Crie as pastas correspondentes no sistema

2. **Para Prelanding Pages**:
   - No arquivo settings.json, adicione múltiplas opções em
     `black.prelanding.folders`
   - Por exemplo: `"folders": ["preland1", "preland2", "preland3"]`
   - Crie as pastas correspondentes no sistema

3. **Configuração de Fluxo de Usuário**:
   - Para manter a consistência nas visitas subsequentes, ative
     `tds.saveuserflow` como `true`
   - Para testar cada acesso independentemente, mantenha como `false`

4. **Suporte a Localização**:
   - Para criar variantes específicas para países, adicione o código do país em
     minúsculas ao nome da pasta
   - Por exemplo: `oferta1br` para direcionamento específico para o Brasil
