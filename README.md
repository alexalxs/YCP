# Sistema de Teste A/B

O sistema YCP possui um mecanismo integrado de teste A/B que permite comparar
diferentes variantes de páginas, direcionamentos e ofertas para determinar qual
tem melhor desempenho. Esta funcionalidade é fundamental para otimização de
campanhas e aumento de conversões.

## Como funciona o teste A/B

O sistema divide o tráfego entre diferentes variantes de acordo com porcentagens
configuradas. Para cada usuário, uma variante é selecionada aleatoriamente (ou
com base em critérios predefinidos) e essa decisão é armazenada em cookies para
manter consistência nas visitas subsequentes.

### Componentes principais

1. **Arquivo abtest.php**: Contém a lógica para seleção e rastreamento de
   variantes
2. **Funções de seleção**: `select_item()`, `select_landing()` e
   `select_prelanding()`
3. **Sistema de cookies**: Mantém a persistência da experiência do usuário

### Configuração

A configuração de testes A/B é feita através do painel administrativo em
`/admin/`:

1. Nome e descrição do teste
2. Período de execução
3. Tipo de teste (white/black, landing/prelanding)
4. Configuração das variantes e distribuição de tráfego
5. Métricas de rastreamento

## Simulação de teste A/B com 3 requisições

Vamos simular um teste A/B com duas variantes de landing page e demonstrar o
comportamento do sistema com 3 requisições diferentes.

### Configuração do teste simulado

```php
$abtests = [
    [
        'name' => 'Teste de Landing Pages',
        'type' => 'black_landing',
        'active' => true,
        'save_flow' => true,
        'variants' => [
            [
                'name' => 'Variante A - CTA Verde',
                'folder' => 'offer1/landing-green',
                'percentage' => 60
            ],
            [
                'name' => 'Variante B - CTA Vermelho',
                'folder' => 'offer1/landing-red',
                'percentage' => 40
            ]
        ]
    ]
];
```

### Requisição 1: Primeiro acesso de um novo visitante

**Requisição HTTP:**

```
GET / HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36
Accept: text/html,application/xhtml+xml
```

**Fluxo de processamento:**

1. Sistema verifica se há teste A/B ativo → Sim
2. Sistema verifica se o usuário já tem cookie de teste → Não
3. Sistema seleciona uma variante com base nas porcentagens (60/40)
4. Neste caso: Selecionada **Variante A**
5. Sistema define cookie: `abtest=variant_a; expires=1742319207; path=/`
6. Sistema carrega a landing page da Variante A
7. Sistema registra uma visualização para Variante A

**Resposta HTTP:**

```
HTTP/1.1 200 OK
Set-Cookie: abtest=variant_a; Expires=1742319207; Path=/; SameSite=None; Secure
Content-Type: text/html; charset=UTF-8

<!DOCTYPE html>
<html>
<head>
    <title>Oferta Especial - CTA Verde</title>
    ...
</head>
<body>
    <!-- Conteúdo da landing page com botão CTA verde -->
    ...
</body>
</html>
```

### Requisição 2: Retorno do mesmo visitante

**Requisição HTTP:**

```
GET / HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36
Accept: text/html,application/xhtml+xml
Cookie: abtest=variant_a
```

**Fluxo de processamento:**

1. Sistema verifica se há teste A/B ativo → Sim
2. Sistema verifica se o usuário já tem cookie de teste → Sim (variant_a)
3. Sistema carrega a mesma variante (A) para manter consistência
4. Sistema registra outra visualização para Variante A

**Resposta HTTP:**

```
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8

<!DOCTYPE html>
<html>
<head>
    <title>Oferta Especial - CTA Verde</title>
    ...
</head>
<body>
    <!-- Mesmo conteúdo da landing page com botão CTA verde -->
    ...
</body>
</html>
```

### Requisição 3: Acesso de um visitante diferente

**Requisição HTTP:**

```
GET / HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15
Accept: text/html,application/xhtml+xml
```

**Fluxo de processamento:**

1. Sistema verifica se há teste A/B ativo → Sim
2. Sistema verifica se o usuário já tem cookie de teste → Não
3. Sistema seleciona uma variante com base nas porcentagens (60/40)
4. Neste caso: Selecionada **Variante B**
5. Sistema define cookie: `abtest=variant_b; expires=1742319207; path=/`
6. Sistema carrega a landing page da Variante B
7. Sistema registra uma visualização para Variante B

**Resposta HTTP:**

```
HTTP/1.1 200 OK
Set-Cookie: abtest=variant_b; Expires=1742319207; Path=/; SameSite=None; Secure
Content-Type: text/html; charset=UTF-8

<!DOCTYPE html>
<html>
<head>
    <title>Oferta Especial - CTA Vermelho</title>
    ...
</head>
<body>
    <!-- Conteúdo da landing page com botão CTA vermelho -->
    ...
</body>
</html>
```

## Análise de resultados

Após acumular dados suficientes, o sistema permite analisar:

- Taxa de cliques por variante
- Taxa de conversão por variante
- Tempo médio na página
- Outros comportamentos configurados para rastreamento

O painel administrativo mostra estas métricas em formato tabular e gráfico,
permitindo identificar qual variante tem melhor desempenho e tomar decisões
baseadas em dados.

## Conclusão

O sistema de teste A/B do YCP fornece uma maneira robusta e confiável de
otimizar páginas e ofertas, permitindo decisões baseadas em dados reais de
comportamento do usuário em vez de suposições. Este mecanismo é essencial para
maximizar conversões e ROI de campanhas.
