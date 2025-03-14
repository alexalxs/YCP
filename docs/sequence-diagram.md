# Sequence Diagram for Presenting Pages from 'white' and 'black' Directories

```mermaid
sequenceDiagram
    participant User as User
    participant Server as Server
    participant Cloaker as Cloaker
    participant WhitePage as White Page
    participant BlackPage as Black Page
    participant Database as Database

    User->>Server: Request Page
    Server->>Cloaker: Check User
Cloaker->>Database: Retrieve User Data (IP, OS, Country, User Agent, ISP)
Database-->>Cloaker: User Data (IP, OS, Country, User Agent, ISP)
    alt User is Filtered
        Cloaker->>WhitePage: Load White Page
        WhitePage-->>Server: White Page Content
        Server-->>User: Display White Page
    else User is Not Filtered
        Cloaker->>BlackPage: Load Black Page
        BlackPage-->>Server: Black Page Content
        Server-->>User: Display Black Page
    end
