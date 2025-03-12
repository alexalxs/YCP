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
require_once 'htmlprocessing.php';
require_once 'cookies.php';
require_once 'redirect.php';
require_once 'requestfunc.php';
require_once 'detect.php';
require_once 'filters.php';

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
    // Log para depuração
    error_log("Arquivo ou diretório existente: " . $file_path);
    
    if (is_dir($file_path)) {
        // Log para depuração
        error_log("É um diretório: " . $file_path);
        
        // Se for um diretório, verificar se existe index.html
        if (file_exists($file_path . '/index.html')) {
            error_log("Lendo index.html: " . $file_path . '/index.html');
            
            // Ler o conteúdo do arquivo
            $html = file_get_contents($file_path . '/index.html');
            
            // Verificar se é uma pasta de oferta
            if (strpos($file_path, '/oferta') !== false || strpos($file_path, '/offers') !== false) {
                error_log("É uma pasta de oferta: " . $file_path);
                
                // Adicionar script de conversão se ainda não estiver incluído
                if (strpos($html, 'conversion_tracker.js') === false && strpos($html, 'email_track.php') === false) {
                    error_log("Script de conversão não encontrado no HTML");
                    
                    // Tentar carregar o script diretamente do arquivo raiz
                    if (file_exists('conversion_tracker.js')) {
                        error_log("Carregando script do arquivo raiz");
                        $script_content = file_get_contents('conversion_tracker.js');
                    } 
                    // Ou do diretório scripts
                    elseif (file_exists('scripts/conversion_tracker.js')) {
                        error_log("Carregando script do diretório scripts");
                        $script_content = file_get_contents('scripts/conversion_tracker.js');
                    }
                    
                    if (!empty($script_content)) {
                        error_log("Script carregado: " . strlen($script_content) . " bytes");
                        
                        if (strpos($html, '</body>') !== false) {
                            error_log("Inserindo script antes de </body>");
                            $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
                        } else {
                            error_log("Adicionando script ao final do HTML");
                            $html .= '<script>' . $script_content . '</script>';
                        }
                    } else {
                        error_log("Script não encontrado ou vazio");
                    }
                } else {
                    error_log("Script de conversão já incluído no HTML");
                }
            } else {
                error_log("Não é uma pasta de oferta: " . $file_path);
            }
            
            // Reescrever URLs relativas
            $baseurl = str_replace(__DIR__, '', $file_path);
            if (substr($baseurl, 0, 1) === '/') {
                $baseurl = substr($baseurl, 1);
            }
            $html = rewrite_relative_urls($html, $baseurl);
            
            // Enviar o conteúdo modificado
            echo $html;
            exit;
        }
    } elseif (is_file($file_path)) {
        error_log("É um arquivo: " . $file_path);
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
            case 'mp3':
                header('Content-Type: audio/mpeg');
                break;
            case 'mp4':
                header('Content-Type: video/mp4');
                break;
            case 'webp':
                header('Content-Type: image/webp');
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
            case 'woff':
                header('Content-Type: font/woff');
                break;
            case 'woff2':
                header('Content-Type: font/woff2');
                break;
            case 'ttf':
                header('Content-Type: font/ttf');
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

// Verificar se a URL solicitada é uma pasta personalizada
if (isset($_GET['custom_type']) && isset($_GET['custom_folder'])) {
    // Debug
    error_log("custom_type: " . $_GET['custom_type'] . ", custom_folder: " . $_GET['custom_folder']);
    
    $custom_type = $_GET['custom_type'];
    $custom_folder = $_GET['custom_folder'];
    
    if ($custom_type === 'white') {
        $html = load_white_content($custom_folder, $use_js_checks);
        
        // Adicionar script de conversão de lead se ainda não estiver incluído
        if (file_exists('scripts/conversion_tracker.js') && strpos($html, 'conversion_tracker.js') === false) {
            $script_content = file_get_contents('scripts/conversion_tracker.js');
            if (strpos($html, '</body>') !== false) {
                $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
            } else {
                $html .= '<script>' . $script_content . '</script>';
            }
        }
        
        echo $html;
        exit;
    }
    
    if ($custom_type === 'offers') {
        $html = load_landing($custom_folder);
        
        // Adicionar script de conversão de lead se ainda não estiver incluído
        if (file_exists('scripts/conversion_tracker.js') && strpos($html, 'conversion_tracker.js') === false) {
            $script_content = file_get_contents('scripts/conversion_tracker.js');
            if (strpos($html, '</body>') !== false) {
                $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
            } else {
                $html .= '<script>' . $script_content . '</script>';
            }
        }
        
        echo $html;
        exit;
    }
}

// Verificar se é uma pasta white personalizada
if (preg_match('#^/white/([^/]+)/?$#', $request_path, $matches)) {
    $folder = $matches[1];
    if (file_exists('white/' . $folder) && is_dir('white/' . $folder)) {
        $html = load_white_content($folder, $use_js_checks);
        
        // Adicionar script de conversão de lead se ainda não estiver incluído
        if (strpos($html, 'conversion_tracker.js') === false && strpos($html, 'email_track.php') === false) {
            // Tentar carregar o script diretamente do arquivo raiz
            if (file_exists('conversion_tracker.js')) {
                $script_content = file_get_contents('conversion_tracker.js');
            } 
            // Ou do diretório scripts
            elseif (file_exists('scripts/conversion_tracker.js')) {
                $script_content = file_get_contents('scripts/conversion_tracker.js');
            }
            
            if (!empty($script_content)) {
                if (strpos($html, '</body>') !== false) {
                    $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
                } else {
                    $html .= '<script>' . $script_content . '</script>';
                }
            }
        }
        
        echo $html;
        exit;
    }
}

// Verificar se é uma pasta de oferta personalizada
if (preg_match('#^/offers/([^/]+)/?$#', $request_path, $matches)) {
    $folder = $matches[1];
    if (file_exists('offers/' . $folder) && is_dir('offers/' . $folder)) {
        $html = load_landing($folder);
        
        // Adicionar script de conversão de lead se ainda não estiver incluído
        if (strpos($html, 'conversion_tracker.js') === false && strpos($html, 'email_track.php') === false) {
            // Tentar carregar o script diretamente do arquivo raiz
            if (file_exists('conversion_tracker.js')) {
                $script_content = file_get_contents('conversion_tracker.js');
            } 
            // Ou do diretório scripts
            elseif (file_exists('scripts/conversion_tracker.js')) {
                $script_content = file_get_contents('scripts/conversion_tracker.js');
            }
            
            if (!empty($script_content)) {
                if (strpos($html, '</body>') !== false) {
                    $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
                } else {
                    $html .= '<script>' . $script_content . '</script>';
                }
            }
        }
        
        echo $html;
        exit;
    }
}

// Resto do código original
$ip = getip();
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$user_language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$user_os = getOS($user_agent);
$user_country = getcountry($ip);
$user_isp = getisp($ip);
$user_city = getcity($ip);
$user_region = getregion($ip);
$user_device = getdevice($user_agent);
$user_browser = getbrowser($user_agent);
$user_token = isset($_GET['token']) ? $_GET['token'] : '';
$user_subid = get_subid();
$user_flow = get_flow();
$user_subacc = get_subacc();
$user_prelanding = get_prelanding();
$user_landing = get_landing();
$user_is_bot = is_bot($user_agent);
$user_is_vpn = is_vpn($ip);
$user_is_tor = is_tor($ip);
$user_is_blocked = false;
$user_block_reason = [];

//Проверяем фильтры
if (check_os_filter($user_os, $os_white) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'os';
}

if (check_country_filter($user_country, $country_white) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'country';
}

if (check_url_filter($_SERVER['REQUEST_URI'], $url_should_contain, $url_mode) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'url';
}

if (check_tokens_filter($user_token, $tokens_black) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'token';
}

if (check_ua_filter($user_agent, $ua_black) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'ua';
}

if (check_isp_filter($user_isp, $isp_black) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'isp';
}

if (check_lang_filter($user_language, $lang_white) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'lang';
}

if (check_referer_filter($referer, $block_without_referer, $referer_stopwords) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'referer';
}

if (check_vpntor_filter($user_is_vpn, $user_is_tor, $block_vpnandtor) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'vpn';
}

if (check_ip_filter($ip, $ip_black_filename, $ip_black_cidr) === false) {
    $user_is_blocked = true;
    $user_block_reason[] = 'ip';
}

if ($user_is_bot) {
    $user_is_blocked = true;
    $user_block_reason[] = 'bot';
}

//Если пользователь заблокирован, то показываем вайт пейдж
if ($user_is_blocked) {
    log_white_click($ip, $user_country, $user_isp, $user_os, $user_agent, $user_block_reason, $_GET);
    switch ($white_action) {
        case 'folder':
            $white_folder = $white_folder_names[array_rand($white_folder_names)];
            $html = load_white_content($white_folder, $use_js_checks);
            echo $html;
            break;
        case 'curl':
            $white_url = $white_curl_urls[array_rand($white_curl_urls)];
            $html = load_white_curl($white_url, $use_js_checks);
            echo $html;
            break;
        case 'redirect':
            $white_url = $white_redirect_urls[array_rand($white_redirect_urls)];
            redirect($white_url, $white_redirect_type);
            break;
        case 'error':
            $error_code = $white_error_codes[array_rand($white_error_codes)];
            http_response_code($error_code);
            break;
    }
    exit;
}

//Если пользователь прошел фильтры, то показываем блэк пейдж
log_black_click($user_subid, $ip, $user_country, $user_isp, $user_os, $user_agent, $user_prelanding, $user_landing, $_GET);

//Если у пользователя уже есть кука, то показываем блэк пейдж
if (isset($_COOKIE['landing'])) {
    $landing = $_COOKIE['landing'];
    switch ($black_land_action) {
        case 'folder':
            $html = load_landing($landing);
            echo $html;
            break;
        case 'redirect':
            $black_land_url = $black_land_redirect_urls[array_rand($black_land_redirect_urls)];
            redirect($black_land_url, $black_land_redirect_type);
            break;
    }
    exit;
}

//Если у пользователя уже есть кука, то показываем преленд
if (isset($_COOKIE['prelanding'])) {
    $prelanding = $_COOKIE['prelanding'];
    $html = load_prelanding($prelanding, 1);
    echo $html;
    exit;
}

//Если нет куки, то показываем преленд или ленд
if ($black_preland_action === 'none') {
    //Показываем ленд
    $landing = $black_land_folder_names[array_rand($black_land_folder_names)];
    ywbsetcookie('landing', $landing, '/');
    switch ($black_land_action) {
        case 'folder':
            $html = load_landing($landing);
            echo $html;
            break;
        case 'redirect':
            $black_land_url = $black_land_redirect_urls[array_rand($black_land_redirect_urls)];
            redirect($black_land_url, $black_land_redirect_type);
            break;
    }
} else {
    //Показываем преленд
    $prelanding = $black_preland_folder_names[array_rand($black_preland_folder_names)];
    ywbsetcookie('prelanding', $prelanding, '/');
    $html = load_prelanding($prelanding, 1);
    echo $html;
}
?>