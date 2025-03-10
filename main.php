<?php
require_once 'htmlprocessing.php';
require_once 'cookies.php';
require_once 'redirect.php';
require_once 'pixels.php';
require_once 'abtest.php';

//Включение отладочной информации
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Конец включения отладочной информации

function white($use_js_checks)
{
    global $white_action,$white_folder_names,$white_redirect_urls,$white_redirect_type;
    global $white_curl_urls,$white_error_codes,$white_use_domain_specific,$white_domain_specific;
    global $save_user_flow;

    // Forçar action para "folder" para garantir carregamento direto
    $action = "folder";
    $folder_names = $white_folder_names;
    
    // Forçar headers para garantir que o conteúdo seja exibido
    header('Content-Type: text/html; charset=UTF-8');
    
    // Para evitar output vazio
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Iniciar novo buffer
    ob_start();
    
    //грязный хак для прокидывания реферера через куки
    if ($use_js_checks && 
        isset($_SERVER['HTTP_REFERER']) && 
        !empty($_SERVER['HTTP_REFERER'])){
        ywbsetcookie("referer",$_SERVER['HTTP_REFERER']);
    }

    $curfolder = select_item($folder_names, $save_user_flow, 'white', true);
    // Verificar se o caminho existe
    $content_path = $curfolder[0].'/index.html';
    
    if (file_exists($content_path)) {
        // Carregar conteúdo diretamente do arquivo
        $html_content = file_get_contents($content_path);
        if (!empty($html_content)) {
            // Processar o HTML para caminhos relativos
            $baseurl = '/'.$curfolder[0].'/';
            $html_content = rewrite_relative_urls($html_content, $baseurl);
            echo $html_content;
        } else {
            // Se o arquivo está vazio, tenta carregar com a função normal
            echo load_white_content($curfolder[0], false);
        }
    } else {
        // Se não encontrar o arquivo, tenta outros métodos
        $content_path = $curfolder[0].'/index.php';
        if (file_exists($content_path)) {
            include $content_path;
        } else {
            echo load_white_content($curfolder[0], false);
        }
    }
    
    // Obter conteúdo do buffer e enviar para o navegador
    $output = ob_get_clean();
    
    // Garantir que há conteúdo antes de enviar
    if (!empty($output)) {
        // Definir tamanho do conteúdo
        header('Content-Length: ' . strlen($output));
        echo $output;
    } else {
        die("Não foi possível carregar a página branca. Verifique se os arquivos existem.");
    }
    
    return;
}

function black($clkrdetect)
{
    global $black_preland_action,$black_preland_folder_names;
    global $black_land_action,$black_land_folder_names, $black_land_redirect_urls, $black_land_redirect_type;
    global $save_user_flow,$jsconnect_type;

    // Forçar headers para garantir que o conteúdo seja exibido
    header('Content-Type: text/html; charset=UTF-8');
    
    // Para evitar output vazio
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Iniciar novo buffer
    ob_start();
    
    $cursubid = set_subid();
    set_facebook_cookies();
 
    // Forçar modo folder para garantir carregamento direto
    $black_land_action = 'folder';
    
    if ($black_preland_action == 'none')
    {
        if (empty($black_land_folder_names)){
            die('No landings configured!');
        }

        // Selecionar landing
        $res = select_landing($save_user_flow, $black_land_folder_names, true);
        $landing = $res[0];
        
        if (empty($landing)) {
            die('Unable to select landing!');
        }

        add_black_click($cursubid, $clkrdetect, '', $landing);

        // Carregar o conteúdo diretamente
        $content_path = $landing.'/index.html';
        
        if (file_exists($content_path)) {
            // Carregar conteúdo diretamente do arquivo
            $html_content = file_get_contents($content_path);
            if (!empty($html_content)) {
                // Processar o HTML para caminhos relativos
                $baseurl = '/'.$landing.'/';
                $html_content = rewrite_relative_urls($html_content, $baseurl);
                echo $html_content;
            } else {
                // Se o arquivo está vazio, tenta carregar com a função normal
                echo load_landing($landing);
            }
        } else {
            // Se não encontrar o arquivo, tenta outros métodos
            $content_path = $landing.'/index.php';
            if (file_exists($content_path)) {
                include $content_path;
            } else {
                echo load_landing($landing);
            }
        }
    }
    else if ($black_preland_action == 'folder')
    {
        // Código para prelands
        $prelandings = $black_preland_folder_names;
        if (empty($prelandings)) {
            die('No prelands configured!');
        }
        
        $res = select_prelanding($save_user_flow, $prelandings);
        $prelanding = $res[0];
        
        $res = select_landing($save_user_flow, $black_land_folder_names, true);
        $landing = $res[0];
        $t = $res[1];

        echo load_prelanding($prelanding, $t);
        add_black_click($cursubid, $clkrdetect, $prelanding, $landing);
    }
    
    // Obter conteúdo do buffer e enviar para o navegador
    $output = ob_get_clean();
    
    // Garantir que há conteúdo antes de enviar
    if (!empty($output)) {
        // Definir tamanho do conteúdo
        header('Content-Length: ' . strlen($output));
        echo $output;
    } else {
        die("Não foi possível carregar a página de oferta. Verifique se os arquivos existem.");
    }
}

?>
