<?php
require_once __DIR__.'/bases/ipcountry.php';

function select_landing($save_user_flow,$landings,$isfolder=false,$test_mode=false){
    // Log para depuração
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("select_landing: save_user_flow=$save_user_flow, is_folder=$isfolder, test_mode=$test_mode");
        error_log("landings: " . print_r($landings, true));
        error_log("Cookie landing: " . (isset($_COOKIE['landing']) ? $_COOKIE['landing'] : 'not set'));
    }
    
    // Se não houver ofertas disponíveis, retornar vazio
    if (empty($landings))
        return array("", 0);
    
    $l = count($landings);
    
    // Se $save_user_flow for false OU modo de teste ativo, sempre selecionar aleatoriamente
    // Isso garante a rotação das ofertas quando a configuração indicar para não manter o mesmo usuário na mesma página
    if (!$save_user_flow || $test_mode || !isset($_COOKIE['landing'])) {
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("Selecting random landing due to: save_user_flow=$save_user_flow, test_mode=$test_mode, cookie_exists=" . (isset($_COOKIE['landing']) ? 'yes' : 'no'));
        }
        
        // Usar random_int se disponível (mais seguro), senão usar mt_rand
        if (function_exists('random_int')) {
            $t = random_int(0, $l - 1);
        } else {
            $t = mt_rand(0, $l - 1);
        }
        
        // Log da seleção aleatória
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("Randomly selected landing index: $t, value: " . $landings[$t]);
        }
        
        return array($landings[$t], $t);
    }
    // Se $save_user_flow for true, verificar se o cookie existe e é válido
    else {
        $landing = $_COOKIE['landing'];
        $t = array_search($landing, $landings);
        
        // Se o cookie não for válido ou a pasta não existir, selecionar aleatoriamente
        if ($t === false || ($isfolder && !is_dir(__DIR__.'/'.$landing))) {
            if (defined('DEBUG_LOG') && DEBUG_LOG) {
                error_log("Cookie value invalid or folder doesn't exist, selecting random landing");
            }
            
            if (function_exists('random_int')) {
                $t = random_int(0, $l - 1);
            } else {
                $t = mt_rand(0, $l - 1);
            }
            
            $landing = $landings[$t];
        }
        
        // Log da seleção baseada em cookie
        if (defined('DEBUG_LOG') && DEBUG_LOG) {
            error_log("Using cookie-based landing selection: $landing");
        }
        
        return array($landing, $t);
    }
}

function select_prelanding($save_user_flow,$prelandings){
    return select_item($prelandings,$save_user_flow,'prelanding',true);
}

function select_item($items,$save_user_flow=false,$itemtype='landing',$isfolder=true){
    $item='';
    if ($save_user_flow && isset($_COOKIE[$itemtype])) {
        $item = $_COOKIE[$itemtype];
        $t=array_search($item, $items);
        if ($t===false) $item='';
        if ($isfolder && !is_dir(__DIR__.'/'.$item)) $item='';
    }
    if ($item===''){
        //A-B тестирование
        $t = rand(0, count($items) - 1);
        $item = $items[$t];
    }
    //если у нас локальная прокла или ленд, то чекаем, есть ли папка под текущее ГЕО
    //если есть, то берём её
    if ($isfolder)
    {
        $country=strtolower(getcountry());
        if (is_dir(__DIR__.'/'.$item.$country))
            $item.=$country;
    }
    ywbsetcookie($itemtype,$item,'/');
    return array($item,$t);
}

function select_item_by_index($items,$index,$isfolder=true){
    $item='';
    if ($index<count($items) && $index>=0)
        $item= $items[$index];
    else{
        $r = rand(0, count($items) - 1);
        $items= $items[$r];
    }
    //если у нас локальная прокла или ленд, то чекаем, есть ли папка под текущее ГЕО
    //если есть, то берём её
    if ($isfolder)
    {
        $country=strtolower(getcountry());
        if (is_dir(__DIR__.'/'.$item.$country))
            $item.=$country;
    }
    return $item;
}
?>
