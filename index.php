<?php
//Включение отладочной информации
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Конец включения отладочной информации

// Debug para key=1
if (isset($_GET['key']) && $_GET['key'] == '1') {
    // Em vez de redirecionar, carregar diretamente o conteúdo da oferta
    require_once 'cookies.php';
    require_once 'main.php';  // Inclui as funções necessárias
    require_once 'settings.php';
    require_once 'db.php';
    require_once 'abtest.php';  // Incluir funções de teste A/B
    
    // Log para depuração
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("==== INÍCIO DO PROCESSAMENTO ====");
        error_log("save_user_flow: " . ($save_user_flow ? 'true' : 'false'));
        error_log("Cookie landing atual: " . (isset($_COOKIE['landing']) ? $_COOKIE['landing'] : 'não definido'));
    }
    
    // Definir cookies e processar adequadamente
    $subid = isset($_COOKIE['subid']) ? $_COOKIE['subid'] : md5(uniqid(rand(), true));
    ywbsetcookie('subid', $subid, '/');
    
    // Inicializar a variável $use_abtest para evitar avisos
    $use_abtest = false;
    
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
        $use_abtest = false; // Não usa A/B quando já tem uma oferta definida para o usuário
    }
    // Caso contrário, selecionar uma landing page aleatoriamente usando o teste A/B
    else {
        // Passamos true como último parâmetro para modo de teste quando force_rotation é true
        $res = select_landing($save_user_flow, $black_land_folder_names, true, $force_rotation);
        $landing = $res[0];
        $use_abtest = true;
        
        // Log adicional para debug
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("Force rotation: " . ($force_rotation ? 'YES' : 'NO'));
            error_log("Selected landing: $landing");
        }
    }
    
    // Definir o cookie landing ANTES de qualquer saída para evitar o erro "headers already sent"
    ywbsetcookie('landing', $landing, '/');
    
    // Registrar o clique para estatísticas (se estiver usando teste A/B)
    if ($use_abtest) {
        // Use cloaker->detect se disponível, ou crie um array com valores padrão
        if (isset($cloaker)) {
            $detect_info = $cloaker->detect;
        } else {
            // Criar um array com valores padrão para evitar avisos de índices indefinidos
            $detect_info = array(
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                'country' => '',
                'os' => '',
                'isp' => '',
                'ua' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''
            );
            
            // Log para depuração
            if (defined('DEBUG_LOG') && DEBUG_LOG) {
                error_log("Usando detect_info padrão (cloaker não definido)");
            }
        }
        add_black_click($subid, $detect_info, '', $landing);
    }
    
    // Carregar o conteúdo diretamente
    $file_path = __DIR__ . '/' . $landing . '/index.html';
    if (file_exists($file_path)) {
        $html = file_get_contents($file_path);
        
        // Ajustar os formulários para submeter para send.php na raiz
        $html = preg_replace('/action="([^"]*send\.php)(\?[^"]*)?"/i', 'action="send.php$2"', $html);
        
        // Ajustar links para recursos CSS e JS, garantindo que apontem para a pasta correta da oferta
        $html = preg_replace('/(href|src)="(styles\.css|script\.js|css\/[^"]+|js\/[^"]+)"/i', '$1="' . $landing . '/$2"', $html);
        
        // Garantir que o parâmetro key=1 seja preservado no formulário
        if (strpos($html, 'name="key"') === false) {
            $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="key" value="1">', $html);
        }
        
        // Adicionar o parâmetro "oferta" ao formulário para identificação
        if (strpos($html, 'name="oferta"') === false) {
            $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="oferta" value="' . $landing . '">', $html);
        }
        
        // Log para depuração
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("==== FIM DO PROCESSAMENTO ====");
        }
        
        echo $html;
        exit;
    }
    
    // Se não encontrar o arquivo, continuar com o fluxo normal
}

require_once 'core.php';
require_once 'settings.php';
require_once 'db.php';
require_once 'main.php';
//передаём все параметры в кло
$cloaker = new Cloaker($os_white,$country_white,$lang_white,$ip_black_filename,$ip_black_cidr,$tokens_black,$url_should_contain,$ua_black,$isp_black,$block_without_referer,$referer_stopwords,$block_vpnandtor);

//если включен full_cloak_on, то шлём всех на white page, полностью набрасываем плащ)
if ($tds_mode=='full') {
    add_white_click($cloaker->detect, ['fullcloak']);
    white(false);
    return;
}

//если используются js-проверки, то сначала используются они
//проверка же обычная идёт далее в файле js/jsprocessing.php
if ($use_js_checks===true) {
	white(true);
}
else{
	//Проверяем зашедшего пользователя
	$check_result = $cloaker->check();

	if ($check_result == 0 || $tds_mode==='off') { //Обычный юзверь или отключена фильтрация
		black($cloaker->detect);
		return;
	} else { //Обнаружили бота или модера
		add_white_click($cloaker->detect, $cloaker->result);
		white(false);
		return;
	}
}