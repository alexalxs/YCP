<?php
require_once 'settings.php';
require_once 'htmlprocessing.php';
require_once 'db.php';
require_once 'url.php';
require_once 'redirect.php';
require_once 'abtest.php';
require_once 'cookies.php';

//Включение отладочной информации
ini_set('display_errors','1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Конец включения отладочной информации

//добавляем в лог факт пробива проклы

$prelanding = get_cookie('prelanding');
$subid = get_subid();
add_lpctr($subid,$prelanding); //запись пробива проклы

$l=isset($_GET['l'])?$_GET['l']:-1;

// Função para verificar se o arquivo HTML contém um formulário de oferta
function has_offer_form($folder) {
    $file_path = __DIR__ . '/' . $folder . '/index.html';
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        // Verifica se o arquivo já tem campos de oferta, produto ou preço
        if (strpos($content, 'name="oferta"') !== false || 
            strpos($content, 'name="product"') !== false || 
            strpos($content, 'name="price"') !== false) {
            return true;
        }
        
        // Verifica se o arquivo tem formulários com action para send.php
        if (strpos($content, 'action="send.php"') !== false ||
            strpos($content, 'action="../send.php"') !== false) {
            return true;
        }
        
        // Verifica se há formulários com botão de compra ou comportamento de checkout
        if (strpos($content, 'comprar-btn') !== false || 
            strpos($content, 'checkout') !== false || 
            strpos($content, 'submit') !== false && 
            (strpos($content, 'order') !== false || strpos($content, 'compra') !== false)) {
            return true;
        }
    }
    return false;
}

// Função auxiliar para adicionar campos hidden aos formulários
function add_hidden_field_to_forms($html, $name, $value) {
    // Padrão de regex mais preciso para encontrar formulários
    preg_match_all('/<form[^>]*>(.*?)<\/form>/is', $html, $matches, PREG_OFFSET_CAPTURE);
    
    // Se não encontrou formulários, retorna HTML original
    if (empty($matches[0])) {
        return $html;
    }
    
    // Gera o campo hidden
    $field = '<input type="hidden" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">';
    
    // Começa a processar do último formulário para não afetar os índices
    for ($i = count($matches[0]) - 1; $i >= 0; $i--) {
        $form_start = $matches[0][$i][1];
        $form_tag_end = strpos($html, '>', $form_start) + 1;
        
        // Insere o campo hidden logo após a abertura da tag form
        $html = substr_replace($html, $field, $form_tag_end, 0);
    }
    
    return $html;
}

switch ($black_land_action){
    case 'folder':
        $landing=select_item_by_index($black_land_folder_names,$l,true);
        
        // Verifica se o arquivo contém um formulário de oferta
        if (has_offer_form($landing)) {
            // Se tiver um formulário de oferta, carrega diretamente sem processamento
            $file_path = __DIR__ . '/' . $landing . '/index.html';
            if (file_exists($file_path)) {
                // Apenas define os cookies necessários
                ywbsetcookie('landing', $landing, '/');
                
                // Coleta todos os parâmetros da URL para preservá-los
                $query_params = $_GET;
                
                // Serve o arquivo diretamente, mas inclui processamento mínimo para parâmetros
                $html = file_get_contents($file_path);
                
                // Preserva os parâmetros importantes como key=1 nos formulários
                if (!empty($query_params)) {
                    // Preservar parâmetros da URL nos formulários
                    $query_string = http_build_query($query_params);
                    
                    // Adiciona os parâmetros ao action dos formulários 
                    $html = preg_replace('/action="([^"]*send\.php)"/i', 'action="$1?' . $query_string . '"', $html);
                    
                    // Se houver key=1, adiciona como campo hidden aos formulários
                    if (isset($query_params['key'])) {
                        $html = add_hidden_field_to_forms($html, 'key', $query_params['key']);
                    }
                    
                    // Adicionar outros parâmetros importantes
                    foreach ($query_params as $key => $value) {
                        if ($key != 'key' && $key != 'l') {
                            $html = add_hidden_field_to_forms($html, $key, $value);
                        }
                    }
                }
                
                echo $html;
                exit;
            }
        }
        
        // Se não tiver um formulário de oferta ou o arquivo não existir, processa normalmente
        echo load_landing($landing);
        break;
    case 'redirect':
        $fullpath = select_item_by_index($black_land_redirect_urls,$l,false);
        $fullpath = add_querystring($fullpath);
        $fullpath = replace_all_macros($fullpath);
        $fullpath = replace_subs_in_link($fullpath);
        redirect($fullpath,$black_land_redirect_type,false);
        break;
}
?>
