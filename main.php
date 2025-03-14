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

    $action = $white_action;
    $folder_names= $white_folder_names;
    $redirect_urls= $white_redirect_urls;
    $curl_urls= $white_curl_urls;
    $error_codes= $white_error_codes;

    //gravar referrer nos cookies para uso futuro
    if ($use_js_checks && 
		isset($_SERVER['HTTP_REFERER']) && 
        !empty($_SERVER['HTTP_REFERER'])){
        ywbsetcookie("referer",$_SERVER['HTTP_REFERER']);
    }

    if ($white_use_domain_specific) { //configurações específicas por domínio
        $curdomain = $_SERVER['SERVER_NAME'];
        foreach ($white_domain_specific as $wds) {
            if ($wds['name']==$curdomain) {
                $wtd_arr = explode(":", $wds['action'], 2);
                $action = $wtd_arr[0];
                switch ($action) {
                    case 'error':
                        $error_codes= [intval($wtd_arr[1])];
                        break;
                    case 'folder':
                        $folder_names = [$wtd_arr[1]];
                        break;
                    case 'curl':
                        $curl_urls = [$wtd_arr[1]];
                        break;
                    case 'redirect':
                        $redirect_urls = [$wtd_arr[1]];
                        break;
                }
                break;
            }
        }
    }

    // Se for caminho raiz e a ação for 'folder', carregar o conteúdo diretamente em vez de redirecionar
    if ($_SERVER['REQUEST_URI'] == '/' && $action == 'folder') {
        $selected_folder = select_item($folder_names, $save_user_flow, 'white', true);
        $folder = $selected_folder[0];
        
        // Carregar o conteúdo diretamente
        $file_path = __DIR__ . '/' . $folder . '/index.html';
        if (file_exists($file_path)) {
            $html = file_get_contents($file_path);
            
            // Adicionar base href para garantir que todos os recursos relativos funcionem
            if (strpos($html, '<base href=') === false) {
                $html = preg_replace('/<head>(.*?)/is', '<head><base href="/' . $folder . '/">\1', $html);
            }
            
            // Ajustar todos os links para serem relativos a partir da raiz (sem o ponto)
            $html = preg_replace('/(href|src|action)="\/([^"]+)"/i', '$1="$2"', $html);
            
            // Corrigir referências de CSS e JS para usar caminhos absolutos
            $html = preg_replace('/<link([^>]*)href="(styles\.css|css\/[^"]+)"([^>]*)>/i', '<link$1href="/' . $folder . '/$2"$3>', $html);
            $html = preg_replace('/<script([^>]*)src="(script\.js|js\/[^"]+)"([^>]*)>/i', '<script$1src="/' . $folder . '/$2"$3>', $html);
            
            // Garantir que links para a raiz também sejam corretos
            $html = preg_replace('/(href|src|action)="\/"/i', '$1="./"', $html);
            
            echo $html;
            exit;
        }
        
        // Se não conseguir carregar o arquivo, tenta redirecionar como fallback
        header('Location: /' . $folder . '/');
        exit;
    }

    // Se for caminho raiz com parâmetros (como key=1), mostrar o conteúdo diretamente
    if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/' && !empty($_GET) && isset($_GET['key']) && $action == 'folder') {
        // Verificar se foi especificada uma oferta na URL através do parâmetro "offer"
        if (isset($_GET['offer']) && in_array('offer' . $_GET['offer'], $white_folder_names)) {
            // Se houver um parâmetro "offer" válido, usar a oferta especificada
            $folder = 'offer' . $_GET['offer'];
            ywbsetcookie('landing', $folder, '/');
            
            // Carregar o arquivo diretamente
            $file_path = __DIR__ . '/' . $folder . '/index.html';
            if (file_exists($file_path)) {
                $html = file_get_contents($file_path);
                
                // Adicionar base href para garantir que todos os recursos relativos funcionem
                if (strpos($html, '<base href=') === false) {
                    $html = preg_replace('/<head>(.*?)/is', '<head><base href="/' . $folder . '/">\1', $html);
                }
                
                // Ajustar todos os links para serem relativos a partir da raiz (sem o ponto)
                $html = preg_replace('/(href|src|action)="\/([^"]+)"/i', '$1="$2"', $html);
                
                // Corrigir referências de CSS e JS para usar caminhos absolutos
                $html = preg_replace('/<link([^>]*)href="(styles\.css|css\/[^"]+)"([^>]*)>/i', '<link$1href="/' . $folder . '/$2"$3>', $html);
                $html = preg_replace('/<script([^>]*)src="(script\.js|js\/[^"]+)"([^>]*)>/i', '<script$1src="/' . $folder . '/$2"$3>', $html);
                
                // Garantir que links para a raiz também sejam corretos
                $html = preg_replace('/(href|src|action)="\/"/i', '$1="./"', $html);
                
                // Ajustar formulários para apontar para send.php na raiz
                $html = preg_replace('/action="([^"]*send\.php)(\?[^"]*)?"/i', 'action="send.php$2"', $html);
                
                // Garantir que o parâmetro key=1 seja preservado no formulário
                if (strpos($html, 'name="key"') === false) {
                    $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="key" value="1">', $html);
                }
                
                // Adicionar o parâmetro "offer" ao formulário se não existir
                if (strpos($html, 'name="offer"') === false) {
                    $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="offer" value="' . $_GET['offer'] . '">', $html);
                }
                
                echo $html;
                exit;
            }
        } else {
            $selected_folder = select_item($folder_names, $save_user_flow, 'white', true);
            $folder = $selected_folder[0];
            $html = load_white_content($folder, $use_js_checks);
            
            // Adicionar base href para garantir que todos os recursos relativos funcionem
            if (strpos($html, '<base href=') === false) {
                $html = preg_replace('/<head>(.*?)/is', '<head><base href="/' . $folder . '/">\1', $html);
            }
            
            // Corrigir referências de CSS e JS para usar caminhos absolutos
            $html = preg_replace('/<link([^>]*)href="(styles\.css|css\/[^"]+)"([^>]*)>/i', '<link$1href="/' . $folder . '/$2"$3>', $html);
            $html = preg_replace('/<script([^>]*)src="(script\.js|js\/[^"]+)"([^>]*)>/i', '<script$1src="/' . $folder . '/$2"$3>', $html);
            
            echo $html;
            exit;
        }
    }

    //verificações de JavaScript
    if ($use_js_checks) {
        switch ($action) {
            case 'error':
            case 'redirect':
                echo load_js_testpage();
                break;
            case 'folder':
                $curfolder= select_item($folder_names,$save_user_flow,'white',true);
                echo load_white_content($curfolder[0], $use_js_checks);
                break;
            case 'curl':
                $cururl=select_item($curl_urls,$save_user_flow,'white',false);
                echo load_white_curl($cururl[0], $use_js_checks);
                break;
        }
    } else {
        switch ($action) {
            case 'error':
                $curcode= select_item($error_codes,$save_user_flow,'white',true);
                http_response_code($curcode[0]);
                break;
            case 'folder':
                $curfolder= select_item($folder_names,$save_user_flow,'white',true);
                echo load_white_content($curfolder[0], false);
                break;
            case 'curl':
                $cururl=select_item($curl_urls,$save_user_flow,'white',false);
                echo load_white_curl($cururl[0], false);
                break;
            case 'redirect':
                $cururl=select_item($redirect_urls,$save_user_flow,'white',false);
                redirect($cururl[0], $white_redirect_type,false);
                break;
        }
    }
    return;
}

function black($clkrdetect)
{
    global $black_preland_action,$black_preland_folder_names;
	global $black_land_action, $black_land_folder_names, $save_user_flow;
	global $black_land_redirect_type,$black_land_redirect_urls;
	global $fbpixel_subname;

    header('Access-Control-Allow-Credentials: true');
    if (isset($_SERVER['HTTP_REFERER'])) {
        $parsed_url=parse_url($_SERVER['HTTP_REFERER']);
        header('Access-Control-Allow-Origin: '.$parsed_url['scheme'].'://'.$parsed_url['host']);
    }
	
	$cursubid=set_subid();
    set_facebook_cookies();

    // Tratar parâmetro key=1 na raiz
    if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/' && isset($_GET['key']) && $_GET['key'] == '1' && $black_land_action == 'folder') {
        // Verificar se já tem uma oferta associada ao cookie - para manter consistência no teste A/B
        $landing = null;
        $use_abtest = true;
        
        // Parâmetro de teste para forçar rotação de ofertas
        $force_rotation = isset($_GET['rotate']) && $_GET['rotate'] == '1';
        
        // Se o parâmetro offer estiver presente, ele tem prioridade (para testes específicos)
        if (isset($_GET['offer']) && in_array('offer' . $_GET['offer'], $black_land_folder_names)) {
            $landing = 'offer' . $_GET['offer'];
            $use_abtest = false; // Não usa A/B quando a oferta é especificada na URL
        }
        // Se o cookie 'landing' estiver definido E $save_user_flow for TRUE E não estamos forçando rotação
        elseif ($save_user_flow && !$force_rotation && isset($_COOKIE['landing']) && in_array($_COOKIE['landing'], $black_land_folder_names)) {
            $landing = $_COOKIE['landing'];
            $use_abtest = false;
        }
        // Caso contrário, selecionar uma landing page aleatoriamente usando o teste A/B
        else {
            // Passamos true como último parâmetro para modo de teste quando force_rotation é true
            $res = select_landing($save_user_flow, $black_land_folder_names, true, $force_rotation);
            $landing = $res[0];
            
            // Log adicional para debug
            if (defined('DEBUG_LOG') && DEBUG_LOG) {
                error_log("Force rotation (main.php): " . ($force_rotation ? 'YES' : 'NO'));
                error_log("Selected landing (main.php): $landing");
            }
        }
        
        // Registrar o clique para estatísticas (sempre)
        add_black_click($cursubid, $clkrdetect, '', $landing);
        
        // Só armazena o cookie se $save_user_flow for true
        if ($save_user_flow) {
            ywbsetcookie('landing', $landing, '/');
        }
        
        // Carregar o arquivo diretamente
        $file_path = __DIR__ . '/' . $landing . '/index.html';
        if (file_exists($file_path)) {
            $html = file_get_contents($file_path);
            
            // Ajustar todos os links para serem relativos a partir da raiz (sem o ponto)
            $html = preg_replace('/(href|src|action)="\/([^"]+)"/i', '$1="$2"', $html);
            
            // Ajustar links para recursos CSS e JS, garantindo que apontem para a pasta correta da oferta
            $html = preg_replace('/(href|src)="(styles\.css|script\.js|css\/[^"]+|js\/[^"]+)"/i', '$1="' . $landing . '/$2"', $html);
            
            // Garantir que links para a raiz também sejam corretos
            $html = preg_replace('/(href|src|action)="\/"/i', '$1="./"', $html);
            
            // Ajustar formulários para apontar para send.php na raiz
            $html = preg_replace('/action="([^"]*send\.php)(\?[^"]*)?"/i', 'action="send.php$2"', $html);
            
            // Garantir que o parâmetro key=1 seja preservado no formulário
            if (strpos($html, 'name="key"') === false) {
                $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="key" value="1">', $html);
            }
            
            // Adicionar o parâmetro "oferta" ao formulário para identificação
            if (strpos($html, 'name="oferta"') === false) {
                $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="oferta" value="' . $landing . '">', $html);
            }
            
            echo $html;
            return;
        }
        
        // Se não conseguir carregar diretamente, usa o método padrão
        echo load_landing($landing);
        return;
    }
    
    // Se for a raiz sem parâmetros e a ação for 'folder', carregar diretamente a landing page
    if ($_SERVER['REQUEST_URI'] == '/' && $black_land_action == 'folder') {
        // Verificar se já tem uma oferta associada ao cookie - para manter consistência no teste A/B
        $landing = null;
        $use_abtest = true;
        
        // Parâmetro de teste para forçar rotação de ofertas
        $force_rotation = isset($_GET['rotate']) && $_GET['rotate'] == '1';
        
        // Se o cookie 'landing' estiver definido E $save_user_flow for TRUE E não estamos forçando rotação
        if ($save_user_flow && !$force_rotation && isset($_COOKIE['landing']) && in_array($_COOKIE['landing'], $black_land_folder_names)) {
            $landing = $_COOKIE['landing'];
            $use_abtest = false;
        }
        // Caso contrário, selecionar uma landing page aleatoriamente usando o teste A/B
        else {
            // Passamos true como último parâmetro para modo de teste quando force_rotation é true
            $res = select_landing($save_user_flow, $black_land_folder_names, true, $force_rotation);
            $landing = $res[0];
            
            // Log adicional para debug
            if (defined('DEBUG_LOG') && DEBUG_LOG) {
                error_log("Force rotation (main.php): " . ($force_rotation ? 'YES' : 'NO'));
                error_log("Selected landing (main.php): $landing");
            }
        }
        
        // Registrar o clique para estatísticas (sempre)
        add_black_click($cursubid, $clkrdetect, '', $landing);
        
        // Só armazena o cookie se $save_user_flow for true
        if ($save_user_flow) {
            ywbsetcookie('landing', $landing, '/');
        }
        
        // Carregar o arquivo diretamente
        $file_path = __DIR__ . '/' . $landing . '/index.html';
        if (file_exists($file_path)) {
            $html = file_get_contents($file_path);
            
            // Ajustar todos os links para serem relativos a partir da raiz (sem o ponto)
            $html = preg_replace('/(href|src|action)="\/([^"]+)"/i', '$1="$2"', $html);
            
            // Ajustar links para recursos CSS e JS, garantindo que apontem para a pasta correta da oferta
            $html = preg_replace('/(href|src)="(styles\.css|script\.js|css\/[^"]+|js\/[^"]+)"/i', '$1="' . $landing . '/$2"', $html);
            
            // Garantir que links para a raiz também sejam corretos
            $html = preg_replace('/(href|src|action)="\/"/i', '$1="./"', $html);
            
            // Ajustar formulários para apontar para send.php na raiz
            $html = preg_replace('/action="([^"]*send\.php)(\?[^"]*)?"/i', 'action="send.php$2"', $html);
            
            echo $html;
            return;
        }
        
        // Se não conseguir carregar diretamente, usa o método padrão
        echo load_landing($landing);
        return;
    }

	$landings=[];
    $isfolderland=false;
	if ($black_land_action=='redirect')
		$landings = $black_land_redirect_urls;
	else if ($black_land_action=='folder')
    {
		$landings = $black_land_folder_names;
        $isfolderland=true;
    }
	
    switch ($black_preland_action) {
        case 'none':
            $res=select_landing($save_user_flow,$landings,$isfolderland);
            $landing=$res[0];
            add_black_click($cursubid, $clkrdetect, '', $landing);

            switch ($black_land_action){
                case 'folder':
                    echo load_landing($landing);
                    break;
                case 'redirect':
                    $fullpath = add_querystring($landing);
                    $fullpath = replace_all_macros($fullpath);
                    $fullpath = replace_subs_in_link($fullpath);
                    redirect($fullpath,$black_land_redirect_type,false);
                    break;
            }
            break;
        case 'folder': //если мы используем локальные проклы
            $prelandings=$black_preland_folder_names;
            if (empty($prelandings)) break;
            $prelanding='';
            $res=select_prelanding($save_user_flow,$prelandings);
            $prelanding = $res[0];
            $res=select_landing($save_user_flow,$landings,$isfolderland);
            $landing=$res[0];
            $t=$res[1];

            echo load_prelanding($prelanding, $t);
            add_black_click($cursubid, $clkrdetect, $prelanding, $landing);
			break;
    }
    return;
}

?>
