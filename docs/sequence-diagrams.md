# Diagramas de Sequência - main.php

## Fluxo White - Carregamento Direto de Conteúdo

```mermaid
sequenceDiagram
    actor User
    participant Router as Router (/)
    participant White as White Function
    participant FileSystem as File System
    participant Cookie as Cookie Manager
    
    User->>Router: GET /?key=1
    Router->>White: white($use_js_checks)
    White->>Cookie: Check Domain Specific Settings
    Cookie-->>White: Domain Settings
    
    White->>FileSystem: select_item(folder_names)
    FileSystem-->>White: Selected Folder
    
    White->>FileSystem: load_white_content(folder)
    FileSystem-->>White: HTML Content
    
    White->>White: Process HTML (Add base href)
    White->>White: Fix Resource Paths
    White-->>User: Return Processed HTML
```

## Fluxo White - Redirecionamento com Domínio Específico

```mermaid
sequenceDiagram
    actor User
    participant Router as Router (/)
    participant White as White Function
    participant Cookie as Cookie Manager
    participant Domain as Domain Handler
    
    User->>Router: GET /
    Router->>White: white($use_js_checks)
    White->>Cookie: Check HTTP_REFERER
    Cookie->>Cookie: ywbsetcookie("referer")
    
    White->>Domain: Check Domain Specific
    Domain->>Domain: Get SERVER_NAME
    Domain-->>White: Domain Action
    
    alt action is redirect
        White->>White: select_item(redirect_urls)
        White-->>User: HTTP Redirect
    end
```

## Fluxo Black - Landing Page com A/B Testing

```mermaid
sequenceDiagram
    actor User
    participant Router as Router (/)
    participant Black as Black Function
    participant ABTest as A/B Test System
    participant Cookie as Cookie Manager
    participant FileSystem as File System
    participant Tracker as Click Tracker
    
    User->>Router: GET /?key=1
    Router->>Black: black($clkrdetect)
    
    Black->>Cookie: set_facebook_cookies()
    Black->>Cookie: set_subid()
    
    Black->>ABTest: select_landing(save_user_flow)
    ABTest-->>Black: Selected Landing
    
    Black->>Tracker: add_black_click()
    Black->>FileSystem: Load Landing Page
    FileSystem-->>Black: HTML Content
    
    Black->>Black: Process HTML
    Black->>Black: Fix Resource Paths
    Black-->>User: Return Processed HTML
```

## Fluxo Black - Pre-landing com Redirecionamento

```mermaid
sequenceDiagram
    actor User
    participant Router as Router (/)
    participant Black as Black Function
    participant ABTest as A/B Test System
    participant PreLanding as Pre-Landing Handler
    participant Landing as Landing Handler
    participant Tracker as Click Tracker
    
    User->>Router: GET /
    Router->>Black: black($clkrdetect)
    
    Black->>PreLanding: select_prelanding()
    PreLanding-->>Black: Selected Pre-landing
    
    Black->>ABTest: select_landing()
    ABTest-->>Black: Selected Landing
    
    Black->>PreLanding: load_prelanding()
    PreLanding-->>Black: Pre-landing Content
    
    Black->>Tracker: add_black_click()
    Black-->>User: Return Pre-landing Content
```
