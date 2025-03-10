<?php
// Configurações básicas
ini_set('display_errors', 0);
error_reporting(0);

// Headers
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// Primeiro verifica se tem string na URL (prioridade máxima)
$has_key = (isset($_GET['key']) && $_GET['key'] === '1') || 
           (isset($_GET['chave']) && $_GET['chave'] === '1') ||
           (isset($_GET['noip']) && $_GET['noip'] === '1'); // Opção para ignorar iptable

// Se não tem key, verifica se é bot
if (!$has_key) {
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $is_bot = (stripos($ua, 'googlebot') !== false || 
               stripos($ua, 'facebookexternalhit') !== false || 
               stripos($ua, 'facebot') !== false);
} else {
    $is_bot = false; // Se tem key, não importa se é bot
}

// Determinar qual arquivo HTML carregar
$file = ($has_key || $is_bot) ? 'oferta/index.html' : 'branca/index.html';

// Ler e exibir o conteúdo diretamente
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Página não encontrada";
}
?>