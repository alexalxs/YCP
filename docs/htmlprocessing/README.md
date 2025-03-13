# Documentação do htmlprocessing.php

# To Fix ( deve alterar a funcionalidade pois esta incorreta):

# To Do ( deve criar a funcionalidade pois esta faltando):

# Test ( não deve auterar a funcionalidade pois esta em fase de teste pelo usuário):

# Done ( não deve auterar a funcionalidade pois esta correta):

## Função load_prelanding

Carrega e processa conteúdo da página de pré-landing. Adiciona scripts de
rastreamento (GTM, Facebook Pixel, TikTok) e modifica os links para apontar para
a landing page correta.

## Função load_landing

Carrega e processa conteúdo da página de landing. Permite carregar o conteúdo
diretamente de arquivos HTML locais ou via CURL, e adiciona campos ocultos para
rastreamento.

## Função insert_additional_scripts

Adiciona scripts adicionais às páginas, como rastreadores de conversão, scripts
para desabilitar cópia de texto, etc.

## Função fix_phone_and_name

Melhora a experiência de formulários convertendo campos de texto para campos de
telefone e adicionando autocompletar.

## Função replace_city_macros

Substitui macros de cidade no HTML baseado no IP do visitante.

## Função fix_anchors

Substitui âncoras padrão por âncoras com rolagem suave.

## Função insert_phone_mask

Adiciona máscaras aos campos de telefone para formatação automática durante a
digitação.

## Função add_images_lazy_load

Adiciona lazy loading às imagens para melhorar o desempenho da página.

## Função load_white_content

Carrega conteúdo white-label a partir de arquivos locais. Suporta carregamento
de pastas personalizadas, incluindo da raiz do site.

## Função load_white_curl

Carrega conteúdo white-label via CURL. Suporta carregamento de pastas
personalizadas, incluindo da raiz do site.

## Função load_js_testpage

Carrega uma página de teste JavaScript.

## Função add_js_testcode

Adiciona código JavaScript de teste a uma página.

## Função insert_subs_into_forms

Insere parâmetros de rastreamento como campos ocultos em formulários.

## Função rewrite_relative_urls

Reescreve URLs relativas para garantir que apontem para os recursos corretos,
independentemente da pasta atual.

## Função remove_scrapbook

Remove atributos relacionados ao ScrapBook que podem estar presentes no HTML.

## Função remove_from_html

Remove elementos específicos do HTML com base em uma lista definida em um
arquivo.

## Função add_lazy_load

Implementa carregamento lazy para imagens com fallback para navegadores que não
suportam IntersectionObserver.
