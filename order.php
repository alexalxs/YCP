<?php
// Arquivo de processamento de pedidos simples
header('Content-Type: text/html; charset=utf-8');

// Logs dos dados recebidos para debug
$log_file = __DIR__ . '/logs/order_log.txt';
$log_dir = dirname($log_file);

if (!file_exists($log_dir)) {
    mkdir($log_dir, 0755, true);
}

// Registra os dados recebidos
$data = date('Y-m-d H:i:s') . " - ORDER RECEIVED\n";
$data .= "POST: " . print_r($_POST, true) . "\n";
$data .= "GET: " . print_r($_GET, true) . "\n";
$data .= "-------------------------------------\n";

file_put_contents($log_file, $data, FILE_APPEND);

// Retorna uma resposta simples
echo '<html>
<head>
    <title>Pedido Recebido</title>
</head>
<body>
    <h1>Pedido Processado com Sucesso</h1>
    <p>Obrigado pelo seu pedido!</p>
</body>
</html>'; 