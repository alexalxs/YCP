<?php
// Configurações básicas
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('output_buffering', 4096);
ini_set('max_execution_time', 30);
ini_set('memory_limit', '128M');

// Iniciar buffer de saída
ob_start();

// Carregar dependências necessárias
require_once 'core.php';
require_once 'settings.php';
require_once 'db.php';
require_once 'main.php';
require_once 'abtest.php';

// Carregar configurações
$settings = json_decode(file_get_contents('settings.json'), true);

// Headers
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Verificar se a requisição é para um arquivo ou diretório existente
$request_uri = $_SERVER['REQUEST_URI'];
$request_path = parse_url($request_uri, PHP_URL_PATH);
$file_path = __DIR__ . $request_path;

// Se for um arquivo ou diretório existente, servir diretamente
if (file_exists($file_path) && $request_path != '/') {
    if (is_dir($file_path)) {
        // Se for um diretório, verificar se existe index.php ou index.html
        if (file_exists($file_path . '/index.php')) {
            include($file_path . '/index.php');
            exit;
        } elseif (file_exists($file_path . '/index.html')) {
            readfile($file_path . '/index.html');
            exit;
        }
    } elseif (is_file($file_path)) {
        // Se for um arquivo, verificar a extensão e definir o tipo MIME apropriado
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'js':
                header('Content-Type: application/javascript');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
            // Adicione mais tipos MIME conforme necessário
        }
        readfile($file_path);
        exit;
    }
}

// Verificar parâmetros de URL permitidos das configurações
$allowed_params = isset($settings['tds']['filters']['allowed']['inurl']) ? $settings['tds']['filters']['allowed']['inurl'] : [];
$has_allowed_param = false;

// Debug
error_log("Parâmetros permitidos: " . print_r($allowed_params, true));
error_log("Query atual: " . $_SERVER['QUERY_STRING']);

// Verificar se algum parâmetro permitido está presente na URL atual
if (!empty($_SERVER['QUERY_STRING'])) {
    foreach ($allowed_params as $allowed_param) {
        if (trim($_SERVER['QUERY_STRING']) === trim($allowed_param)) {
            $has_allowed_param = true;
            error_log("Parâmetro permitido encontrado: " . $allowed_param);
            break;
        }
    }
}

error_log("Has allowed param: " . ($has_allowed_param ? "true" : "false"));

// Verificar se é um bot permitido
$is_allowed_bot = false;
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $allowed_bots = ['googlebot', 'bingbot', 'yandexbot'];
    foreach ($allowed_bots as $bot) {
        if (stripos($ua, $bot) !== false) {
            $is_allowed_bot = true;
            break;
        }
    }
}

// Inicializar o cloaker com as configurações
$cloaker = new Cloaker($os_white, $country_white, $lang_white, $ip_black_filename, $ip_black_cidr, $tokens_black, $url_should_contain, $url_mode, $ua_black, $isp_black, $block_without_referer, $referer_stopwords, $block_vpnandtor);

// Se o modo TDS estiver definido como "full", enviar todos para white page
if ($tds_mode == 'full') {
    add_white_click($cloaker->detect, ['fullcloak']);
    white(false);
    exit;
}

// Se estiver usando verificações JS, usar primeiro
if ($use_js_checks === true) {
    white(true);
    exit;
} else {
    // Se não tiver parâmetro permitido e não for bot permitido, mostrar white page
    if (!$has_allowed_param && !$is_allowed_bot && $tds_mode !== 'off') {
        error_log("Redirecionando para white page - parâmetro não permitido");
        add_white_click($cloaker->detect, ['invalid_param']);
        white(false);
        exit;
    } else {
        // Verificar o usuário normalmente
        $check_result = $cloaker->check();
        
        if ($check_result == 0) { // Usuário normal
            black($cloaker->detect);
            exit;
        } else { // Bot ou moderador detectado
            add_white_click($cloaker->detect, $cloaker->result);
            white(false);
            exit;
        }
    }
}

// Ler e exibir o conteúdo diretamente
if (file_exists($file)) {
    $content = file_get_contents($file);
    
    // Remover quebras de linha desnecessárias e espaços em branco
    $content = preg_replace('/\s+/', ' ', $content);
    $content = preg_replace('/>\s+</', '><', $content);
    
    echo $content;
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Página não encontrada";
}

// Limpar e enviar o buffer
ob_end_flush();
?>