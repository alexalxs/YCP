<?php
/**
 * Sistema de Rastreamento de Conversões - Yellow Cloaker
 * Script de processamento de conversões de email
 */

// Permitir requisições AJAX
header('Content-Type: application/json');

// Definir constantes
define('LOG_PATH', 'logs');
define('CONVERSION_LOG', LOG_PATH . '/email_conversions.log');
define('STATS_PASSWORD', isset($log_password) ? $log_password : '12345');

// Criar diretório de logs se não existir
if (!file_exists(LOG_PATH)) {
    mkdir(LOG_PATH, 0755, true);
}

// Obter dados da requisição
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$oferta = isset($_POST['oferta']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['oferta']) : 'desconhecida';
$redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'https://site-destino.com';
$timestamp = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = isset($_POST['user_agent']) ? $_POST['user_agent'] : $_SERVER['HTTP_USER_AGENT'];
$referrer = isset($_POST['referrer']) ? $_POST['referrer'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
$page_url = isset($_POST['page_url']) ? $_POST['page_url'] : '';
$subid = isset($_COOKIE['subid']) ? $_COOKIE['subid'] : uniqid('sub_');

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'error' => 'Email inválido',
        'redirect_url' => $redirect_url
    ]);
    exit;
}

// Dados para log
$log_data = [
    'id' => uniqid(),
    'timestamp' => $timestamp,
    'email' => $email,
    'oferta' => $oferta,
    'ip' => $ip,
    'user_agent' => $user_agent,
    'referrer' => $referrer,
    'page_url' => $page_url,
    'subid' => $subid
];

// Gravar no log
$log_entry = json_encode($log_data) . "\n";
$log_status = file_put_contents(CONVERSION_LOG, $log_entry, FILE_APPEND);

// Retornar resultado
if ($log_status !== false) {
    echo json_encode([
        'success' => true,
        'message' => 'Conversão registrada com sucesso',
        'data' => [
            'email' => $email,
            'oferta' => $oferta,
            'timestamp' => $timestamp
        ],
        'redirect_url' => $redirect_url
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Falha ao registrar conversão',
        'redirect_url' => $redirect_url
    ]);
} 