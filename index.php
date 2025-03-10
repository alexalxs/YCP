<?php
// Configurações básicas
ini_set('display_errors', 0);
error_reporting(0);

// Carregar dependências necessárias
require_once 'db.php';

// Headers
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// Coletar informações do usuário para logs
$ip = $_SERVER['REMOTE_ADDR'];
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$country = 'BR'; // Simplificado, no original isto é detectado
$os = 'Unknown'; // Simplificado, no original isto é detectado
$isp = 'Unknown'; // Simplificado, no original isto é detectado

// Criar um array com os dados do usuário para logging
$userdata = [
    'ip' => $ip,
    'ua' => $ua,
    'country' => $country,
    'os' => $os,
    'isp' => $isp
];

// Verifica se tem string na URL (único método de acesso à oferta)
$has_key = (isset($_GET['key']) && $_GET['key'] === '1') || 
           (isset($_GET['chave']) && $_GET['chave'] === '1') ||
           (isset($_GET['noip']) && $_GET['noip'] === '1'); // Opção para ignorar iptable

// Determinar qual arquivo HTML carregar
$file = $has_key ? 'oferta/index.html' : 'branca/index.html';

// Registrar a visita
if ($has_key) {
    // Se for acesso direto à oferta, registra como black_click
    $subid = uniqid('s', true);
    add_black_click($subid, $userdata, '', 'oferta');
} else {
    // Se for acesso à página branca, registra como white_click
    add_white_click($userdata, ['redirect']);
}

// Ler e exibir o conteúdo diretamente
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Página não encontrada";
}
?>