<?php
require_once 'js/obfuscator.php';
require_once 'bases/ipcountry.php';
require_once 'requestfunc.php';
require_once 'pixels.php';
require_once 'htmlinject.php';
require_once 'url.php';
require_once 'cookies.php';

// Defina como true para desabilitar o cache durante o desenvolvimento
if (!defined('DEBUG_DISABLE_CACHE')) {
    define('DEBUG_DISABLE_CACHE', false);
}

//Подгрузка контента блэк проклы из другой папки через CURL
function load_prelanding($url, $land_number)
{
	global $replace_prelanding, $replace_prelanding_address;

    $fullpath = get_abs_from_rel($url);
    $fpwqs = get_abs_from_rel($url,true);

    $html=get_html($fpwqs);
    $html=remove_scrapbook($html);
    $html=remove_from_html($html,'removepreland.html');

    //чистим тег <head> от всякой ненужной мути
    $html=preg_replace('/<head [^>]+>/','<head>',$html);
    $html=insert_after_tag($html,"<head>","<base href='".$fullpath."'>");
    //ВСЕ ПИКСЕЛИ
    //добавляем в страницу скрипт GTM
    $html = insert_gtm_script($html);
    //добавляем в страницу скрипт Yandex Metrika
    $html = insert_yandex_script($html);
    //добавляем всё для пикселя Facebook
    $html=insert_fbpixel_pageview($html);
    $html=insert_fbpixel_viewcontent($html,$url);
    //добавляем всё для пикселя TikTok
    $html=insert_ttpixel_pageview($html);
    $html=insert_ttpixel_viewcontent($html,$url);

    $html = replace_city_macros($html);
    $html = fix_phone_and_name($html);
    $html = insert_phone_mask($html);
    //добавляем во все формы сабы
    $html = insert_subs_into_forms($html);
    //добавляем в формы id пикселя фб
    $html = insert_fbpixel_id($html);

    $domain = get_domain_with_prefix();
    $querystr = $_SERVER['QUERY_STRING'];
    //замена всех ссылок на прокле на универсальную ссылку ленда landing.php
    $replacement = "\\1".$domain.'/landing.php?l='.$land_number.(!empty($querystr)?'&'.$querystr:'');

    //убираем target=_blank если был изначально на прокле
    $html = preg_replace('/(<a[^>]+)(target="_blank")/i', "\\1", $html);

    //если мы будем подменять преленд при переходе на ленд, то ленд надо открывать в новом окне
    if ($replace_prelanding) {
        $replacement=$replacement.'" target="_blank"';
        $url = replace_all_macros($replace_prelanding_address); //заменяем макросы
        $url = add_subs_to_link($url); //добавляем сабы
        $html = insert_file_content_with_replace($html, 'replaceprelanding.js', '</body>', '{REDIRECT}', $url);
    }
    $html = preg_replace('/(<a[^>]+href=")(?!whatsapp:|mailto:|tel:)([^"]*)/', $replacement, $html);
    //убираем левые обработчики onclick у ссылок
    $html = preg_replace('/(<a[^>]+)(onclick="[^"]+")/i', "\\1", $html);
    $html = preg_replace("/(<a[^>]+)(onclick='[^']+')/i", "\\1", $html);

    $html = insert_additional_scripts($html);
    $html = add_images_lazy_load($html);

    return $html;
}

//Подгрузка контента блэк ленда из другой папки через CURL
function load_landing($url)
{
    global $replace_landing, $replace_landing_address, $black_land_log_conversions_on_button_click, $replace_back_address,$back_button_action,$disable_text_copy,$black_land_use_phone_mask,$black_land_phone_mask,$comebacker,$callbacker,$addedtocart, $images_lazy_load;

    // Obtém o caminho completo da landing page
    $fullpath = get_abs_from_rel($url);
    $fpwqs = get_abs_from_rel($url, true);

    // Carrega o conteúdo HTML da página
    $html = get_html($fpwqs);
    
    // Remove elementos desnecessários e faz ajustes básicos
    $html = remove_scrapbook($html);
    $html = remove_from_html($html, 'removeland.html');
    $html = preg_replace('/<head [^>]+>/', '<head>', $html);
    
    // Verifica se estamos na raiz do site (URL padrão) ou em um URL específico
    $is_root_url = ($_SERVER['REQUEST_URI'] == '/' || parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/');
    
    // Se não estamos na raiz, adiciona a base href normal
    if (!$is_root_url) {
        $html = insert_after_tag($html, "<head>", "<base href='" . $fullpath . "'>");
    } else {
        // Se estamos na raiz, ajustar todos os links para serem relativos
        // Não adiciona base href, mas ajusta os links diretamente
        $html = preg_replace('/(href|src|action)="\/([^"]+)"/i', '$1="./$2"', $html);
        $html = preg_replace('/(href|src|action)="\/"/i', '$1="./"', $html);
        
        // Ajusta formulários para apontar para send.php na raiz
        $html = preg_replace('/action="([^"]*\/)?send\.php(\?[^"]*)?"/i', 'action="send.php$2"', $html);
    }
    
    // Adiciona scripts de análise e pixel
    $html = insert_gtm_script($html);
    $html = insert_yandex_script($html);
    $html = insert_fbpixel_pageview($html);
    $html = insert_fbpixel_viewcontent($html, $url);
    $html = insert_ttpixel_pageview($html);
    $html = insert_ttpixel_viewcontent($html, $url);
    
    // Verifica se a página contém um formulário de oferta
    $has_offer_form = strpos($html, 'name="oferta"') !== false || 
                      strpos($html, 'name="product"') !== false || 
                      strpos($html, 'name="price"') !== false ||
                      strpos($html, 'action="send.php"') !== false ||
                      strpos($html, 'action="../send.php"') !== false;
    
    // Preservar parâmetro key=1 e outros parâmetros da URL para formulários
    $query_params = [];
    if (isset($_GET['key'])) {
        $query_params['key'] = $_GET['key'];
    }
    
    // Adiciona outros parâmetros importantes da URL original
    foreach ($_GET as $key => $value) {
        if (!isset($query_params[$key]) && $key != 'l') {
            $query_params[$key] = $value;
        }
    }
    
    // Se a página contém formulários de oferta, atualiza o action para preservar os parâmetros
    if ($has_offer_form && !empty($query_params)) {
        // Adiciona os parâmetros ao action dos formulários
        $query_string = http_build_query($query_params);
        $html = preg_replace('/(action="[^"]*send\.php)(")/i', '$1?' . $query_string . '$2', $html);
        $html = preg_replace('/(action="[^"]*\/send\.php)(")/i', '$1?' . $query_string . '$2', $html);
    }
    
    // Processa formulários e elementos específicos
    if (!$has_offer_form) {
        // Se não for um formulário de oferta, processa normalmente
        $html = insert_subs_into_forms($html);
        $html = insert_fbpixel_id($html);
    }
    
    $html = replace_city_macros($html);
    $html = fix_phone_and_name($html);
    $html = insert_phone_mask($html);
    
    if ($comebacker) {
        $html = insert_comebacker($html);
    }
    
    if ($callbacker) {
        $html = insert_callbacker($html);
    }
    
    if ($addedtocart) {
        $html = insert_addedtocart($html);
    }
    
    if($back_button_action=='replace'){
        $html = fix_back_button($html);
    }
    
    if($disable_text_copy){
        $html = disable_copy($html);
    }
    
    if($images_lazy_load){
        $html = add_lazy_load($html);
    }
    
    // Adiciona scripts para conversão baseada em cliques, se necessário
    if ($black_land_log_conversions_on_button_click) {
        $html = add_buttonclick_conversion($html);
    }
    
    // Se estiver substituindo a landing page, redireciona
    if ($replace_landing) {
        return get_redirect_page($replace_landing_address);
    }
    
    return $html;
}

//добавляем доп.скрипты
function insert_additional_scripts($html)
{
    global $disable_text_copy, $back_button_action, $replace_back_button, $replace_back_address, $add_tos;
    global $comebacker, $callbacker, $addedtocart;

    // Adicionar script de conversão de lead
    if (file_exists('scripts/conversion_tracker.js')) {
        $html = insert_file_content($html, 'conversion_tracker.js', '</body>');
    }

    if ($disable_text_copy) {
        $html = insert_file_content($html, 'disablecopy.js', '</body>');
    }

	switch($back_button_action){
		case 'disable':
			$html = insert_file_content($html, 'disableback.js', '</body>');
			break;
		case 'replace':
			$url= replace_all_macros($replace_back_address); //заменяем макросы
			$url = add_subs_to_link($url); //добавляем сабы
			$html = insert_file_content_with_replace($html, 'replaceback.js', '</body>', '{RA}', $url);
			break;
	}

    if ($add_tos) {
        $html = insert_file_content($html, 'tos.html', '</body>');
    }

    if ($callbacker){
        $html = insert_file_content($html,'callbacker/head.html','</head>');
        $html = insert_file_content($html,'callbacker/template.html','</body>');
    }

    if ($comebacker){
        $html = insert_file_content($html,'comebacker/head.html','</head>');
        $html = insert_file_content($html,'comebacker/template.html','</body>');
    }

    if ($addedtocart){
        $html = insert_file_content($html,'addedtocart/head.html','</head>');
        $html = insert_file_content($html,'addedtocart/template.html','</body>');
    }
    
    return $html;
}

//если тип поля телефона - text, меняем его на tel для более удобного ввода с мобильных
//добавляем autocomplete к полям name и phone
function fix_phone_and_name($html)
{
    $firstr='/(<input[^>]*name="(phone|tel)"[^>]*type=")(text)("[^>]*>)/';
    $secondr='/(<input[^>]*type=")(text)("[^>]*name="(phone|tel)"[^>]*>)/';
    $html = preg_replace($secondr, "\\1tel\\3", $html);
    $html = preg_replace($firstr, "\\1tel\\4", $html);

    //добавляем autocomplete к телефонам
    $telacmpltr='/<input[^>]*type="tel"[^>]*>/';
    if (preg_match_all($telacmpltr,$html,$matches,PREG_OFFSET_CAPTURE)){
        for($i=count($matches[0])-1;$i>=0;$i--){
            if (strpos($matches[0][$i][0],"autocomplete")===false){
                $replacement='<input autocomplete="tel"'.substr($matches[0][$i][0],6);
                $html=substr_replace($html,$replacement,$matches[0][$i][1],strlen($matches[0][$i][0]));
            }
        }
    }
    //добавляем autocomplete к именам
    $nameacmpltr='/<input[^>]*name="name"[^>]*>/';
    if (preg_match_all($nameacmpltr,$html,$matches,PREG_OFFSET_CAPTURE)){
        for($i=count($matches[0])-1;$i>=0;$i--){
            if (strpos($matches[0][$i][0],"autocomplete")===false){
                $replacement='<input autocomplete="name"'.substr($matches[0][$i][0],6);
                $html=substr_replace($html,$replacement,$matches[0][$i][1],strlen($matches[0][$i][0]));
            }
        }
    }

    return $html;
}

function replace_city_macros($html){
    $ip = getip();
    $html=preg_replace_callback('/\{CITY,([^\}]+)\}/',function ($m) use($ip){return getcity($ip,$m[1]);},$html);
    return $html;
}

function fix_anchors($html){
    return insert_file_content($html,"replaceanchorswithsmoothscroll.js","<body>",false);
}

function insert_phone_mask($html)
{
    global $black_land_use_phone_mask,$black_land_phone_mask;
	if (!$black_land_use_phone_mask) return $html;
	$domain = get_domain_with_prefix();
    $html = insert_before_tag($html, '</head>', "<script src=\"".$domain."/scripts/inputmask/inputmask.js\"></script>");
    $html = insert_file_content_with_replace($html,'inputmask/inputmaskbinding.js','</body>','{MASK}',$black_land_phone_mask);
    return $html;
}

function add_images_lazy_load($html){
    global $images_lazy_load;
    if (!$images_lazy_load) return $html;
    $html = preg_replace('/(<img\s)((?!.*?loading=([\'\"])[^\'\"]+\3)[^>]*)(>)/s', '<img loading="lazy" \\2\\4', $html);
    return $html;
}

//Подгрузка контента вайта ИЗ ПАПКИ
function load_white_content($url, $add_js_check)
{
    global $fb_use_pageview;
    
    // Verificar explicitamente se a URL está vazia
    if (empty($url)) {
        error_log("URL vazia passada para load_white_content()");
        $url = "white"; // Valor padrão
    }
    
    // Verificar cache primeiro
    $cache_key = "white_" . $url . ($add_js_check ? "_js" : "");
    $cached_content = get_cached_content($cache_key);
    if ($cached_content !== false) {
        return $cached_content;
    }
    
    // Verificar se a URL é uma pasta criada pelo usuário
    $is_custom_white = false;
    $is_custom_offer = false;
    $custom_path = '';
    
    // Verificar se é uma pasta white personalizada ou a própria pasta white
    if (file_exists('white/' . $url) && is_dir('white/' . $url)) {
        $is_custom_white = true;
        $custom_path = 'white/' . $url;
    } 
    elseif (file_exists('white') && is_dir('white') && (empty($url) || $url == 'white' || $url == '/')) {
        $is_custom_white = true;
        $custom_path = 'white';
    }
    // Verificar se é uma pasta offers
    elseif (file_exists('offers/' . $url) && is_dir('offers/' . $url)) {
        $is_custom_offer = true;
        $custom_path = 'offers/' . $url;
    }
    
    // Se for uma pasta personalizada, carregar o index.html dela
    if ($is_custom_white || $is_custom_offer) {
        $index_path = $custom_path . '/index.html';
        
        if (file_exists($index_path)) {
            $html = file_get_contents($index_path);
            
            // Adicionar base href para garantir que os recursos sejam carregados corretamente
            $html = preg_replace('/<head[^>]*>/', '<head><base href="/' . $custom_path . '/">', $html);
            
            // Processar formulários para adicionar campo oculto de oferta se for uma oferta
            if ($is_custom_offer) {
                $offer_name = basename($custom_path);
                $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="oferta" value="' . $offer_name . '">', $html);
            }
            
            // Adicionar script de conversão de lead se necessário
            if (file_exists('scripts/conversion_tracker.js')) {
                $script_content = file_get_contents('scripts/conversion_tracker.js');
                if (strpos($html, '</body>') !== false) {
                    $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
                } else {
                    $html .= '<script>' . $script_content . '</script>';
                }
            }
            
            // Se necessário, adicionar verificação JS
            if ($add_js_check) {
                $html = add_js_testcode($html);
            }
            
            // Salvar em cache
            save_cached_content($cache_key, $html);
            return $html;
        } else {
            error_log("Arquivo index.html não encontrado em $custom_path");
            // Retornar página de erro amigável
            $error_html = "<html><head><title>Conteúdo não disponível</title></head><body>
                        <h1>Página não encontrada</h1>
                        <p>O conteúdo solicitado não está disponível. Por favor, verifique o URL ou retorne para a <a href='/'>página inicial</a>.</p>
                        </body></html>";
            save_cached_content($cache_key, $error_html);
            return $error_html;
        }
    } 
    
    // Comportamento original para URLs não personalizadas
    $fullpath = get_abs_from_rel($url,true);
    
    // Registro para depuração
    error_log("Carregando white content de: $url, fullpath=$fullpath");
    
    $html = get_html($fullpath);
    
    // Verificar se conseguimos obter o HTML
    if (empty($html)) {
        error_log("Falha ao carregar conteúdo white de $url");
        $error_html = "<html><head><title>Conteúdo não disponível</title></head><body>
                    <h1>Conteúdo temporariamente indisponível</h1>
                    <p>O conteúdo solicitado não pode ser carregado. Por favor, tente novamente mais tarde.</p>
                    </body></html>";
        save_cached_content($cache_key, $error_html);
        return $error_html;
    }
    
    $baseurl = '/'.$url.'/';
    
    // Adicionar base href para garantir que os recursos sejam carregados corretamente
    $html = preg_replace('/<head[^>]*>/', '<head><base href="' . $baseurl . '">', $html);
    
    //добавляем в страницу скрипт GTM
    $html = insert_gtm_script($html);
    //добавляем в страницу скрипт Yandex Metrika
    $html = insert_yandex_script($html);
    //добавляем в страницу скрипт Facebook Pixel с событием PageView
    if ($fb_use_pageview) {
        $html = insert_fbpixel_script($html, 'PageView');
    }

    //если на вайте есть форма, то меняем её обработчик, чтобы у вайта и блэка была одна thankyou page
    $html = preg_replace('/\saction=[\'\"]([^\'\"]+)[\'\"]/', " action=\"../worder.php?".http_build_query($_GET)."\"", $html);

    //добавляем в <head> пару доп. метатегов
    $html = str_replace('<head>', '<head><meta name="referrer" content="no-referrer"><meta name="robots" content="noindex, nofollow">', $html);

    //если нужно, добавляем в страницу проверку на js
    if ($add_js_check) {
        $html = add_js_testcode($html);
    }
    
    // Salvar em cache
    save_cached_content($cache_key, $html);
    return $html;
}

//Подгрузка контента вайта через CURL
function load_white_curl($url, $add_js_check)
{
    global $fb_use_pageview;
    
    // Verificar URL vazia
    if (empty($url)) {
        error_log("URL vazia passada para load_white_curl()");
        $url = "white"; // Valor padrão
    }
    
    // Verificar cache primeiro
    $cache_key = "white_curl_" . $url . ($add_js_check ? "_js" : "");
    $cached_content = get_cached_content($cache_key);
    if ($cached_content !== false) {
        return $cached_content;
    }
    
    // Verificar se a URL é uma pasta criada pelo usuário
    $is_custom_white = false;
    $is_custom_offer = false;
    $custom_path = '';
    
    // Verificar se é uma pasta white personalizada ou a raiz
    if (file_exists('white/' . $url) && is_dir('white/' . $url)) {
        $is_custom_white = true;
        $custom_path = 'white/' . $url;
    }
    elseif (file_exists('white') && is_dir('white') && (empty($url) || $url == 'white' || $url == '/')) {
        $is_custom_white = true;
        $custom_path = 'white';
    }
    
    // Verificar se é uma pasta de oferta personalizada
    if (file_exists('offers/' . $url) && is_dir('offers/' . $url)) {
        $is_custom_offer = true;
        $custom_path = 'offers/' . $url;
    }
    
    // Se for uma pasta personalizada, carregar o index.html dela
    if ($is_custom_white || $is_custom_offer) {
        $index_path = $custom_path . '/index.html';
        
        if (file_exists($index_path)) {
            $html = file_get_contents($index_path);
            $baseurl = '/' . $custom_path . '/';
            
            // Adicionar base href para garantir que os recursos sejam carregados corretamente
            $html = preg_replace('/<head[^>]*>/', '<head><base href="' . $baseurl . '">', $html);
            
            // Adicionar script de conversão de lead
            if (file_exists('scripts/conversion_tracker.js')) {
                // Verificar se há tag </body>
                if (strpos($html, '</body>') !== false) {
                    $script_content = file_get_contents('scripts/conversion_tracker.js');
                    $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
                } else {
                    // Se não houver tag </body>, adicionar ao final
                    $script_content = file_get_contents('scripts/conversion_tracker.js');
                    $html .= '<script>' . $script_content . '</script>';
                }
            }
            
            // Processar formulários para adicionar campo oculto de oferta
            if ($is_custom_offer) {
                $offer_name = basename($custom_path);
                $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="oferta" value="' . $offer_name . '">', $html);
            }
            
            // Reescrever URLs relativas
            $html = rewrite_relative_urls($html, $baseurl);
            
            // Adicionar verificação JS se necessário
            if ($add_js_check) {
                $html = add_js_testcode($html);
            }
            
            // Salvar em cache
            save_cached_content($cache_key, $html);
            return $html;
        } else {
            // Se não houver index.html, retornar uma mensagem de erro
            error_log("Arquivo index.html não encontrado em $custom_path");
            $error_html = '<html><head><title>Erro</title></head><body><h1>Erro</h1><p>Arquivo index.html não encontrado na pasta ' . $url . '.</p></body></html>';
            save_cached_content($cache_key, $error_html);
            return $error_html;
        }
    } else {
        // Comportamento original para URLs não personalizadas
        error_log("Carregando URL externa: $url");
        $html = get_html($url, true, true);
        
        if (empty($html)) {
            error_log("Falha ao carregar conteúdo externo de $url");
            $error_html = '<html><head><title>Erro</title></head><body><h1>Erro</h1><p>Não foi possível carregar o conteúdo de ' . $url . '.</p></body></html>';
            save_cached_content($cache_key, $error_html);
            return $error_html;
        }
        
        $baseurl = $url;
    }
    
    $html = rewrite_relative_urls($html, $baseurl);

    //удаляем лишние палящие теги
    $html = preg_replace('/(<meta property=\"og:url\" [^>]+>)/', "", $html);
    $html = preg_replace('/(<link rel=\"canonical\" [^>]+>)/', "", $html);

    //добавляем в страницу скрипт Facebook Pixel
    $html = insert_fbpixel_script($html, 'PageView');

    //добавляем в <head> пару доп. метатегов
    $html= str_replace('<head>', '<head><meta name="referrer" content="no-referrer"><meta name="robots" content="noindex, nofollow">', $html);

    if ($add_js_check) {
        $html = add_js_testcode($html);
    }
    
    // Adicionar scripts adicionais (incluindo o rastreamento de conversões)
    $html = insert_additional_scripts($html);
    
    // Extrair o nome da oferta da URL
    $oferta = '';
    $url_parts = explode('/', trim($url, '/'));
    if (!empty($url_parts)) {
        $oferta = end($url_parts);
    }
    
    // Processar formulários para adicionar rastreamento de conversões
    $html = preg_replace_callback(
        '/<form\s+[^>]*>/i',
        function ($matches) use ($oferta) {
            return $matches[0] . '<input type="hidden" name="oferta" value="' . $oferta . '">';
        },
        $html
    );
    
    // Processar formulários sem action
    $html = preg_replace_callback(
        '/<form\s+[^>]*action=["\']?([^"\'\s>]*)(["\'\s>])/i',
        function ($matches) {
            // Se o formulário não tem action ou tem action vazia, adiciona o action para email_track.php
            if (empty($matches[1]) || $matches[1] == '#' || $matches[1] == 'javascript:void(0)') {
                return '<form action="/email_track.php" method="POST" ' . $matches[2];
            }
            return $matches[0];
        },
        $html
    );
    
    // Salvar em cache
    save_cached_content($cache_key, $html);
    return $html;
}

function load_js_testpage()
{
    $test_page= file_get_contents(__DIR__.'/js/testpage.html');
    return add_js_testcode($test_page);
}

function add_js_testcode($html)
{
    global $js_obfuscate;
    $port = get_port();
    $jsCode= str_replace('{DOMAIN}', $_SERVER['SERVER_NAME'].":".$port, file_get_contents(__DIR__.'/js/connect.js'));
    if ($js_obfuscate) {
        $hunter = new HunterObfuscator($jsCode);
        $jsCode = $hunter->Obfuscate();
    }
	$needle = '</body>';
	if (strpos($html,$needle)===false) $needle = '</html>';
    return str_replace($needle, "<script id='connect'>".$jsCode."</script>".$needle, $html);
}

//вставляет все сабы в hidden полях каждой формы
function insert_subs_into_forms($html)
{
    global $sub_ids;

    // Verifica se $sub_ids está definido e não é vazio
    if (!isset($sub_ids) || empty($sub_ids)) {
        return $html;
    }

    // Procura por todos os formulários na página
    preg_match_all('/<form[^>]*>(.*?)<\/form>/is', $html, $forms, PREG_SET_ORDER);
    
    foreach ($forms as $form) {
        $original_form = $form[0];
        $form_content = $form[1];
        
        // Verifica se o formulário já tem o campo oferta, product ou price para evitar duplicações
        if (strpos($form_content, 'name="oferta"') !== false || 
            strpos($form_content, 'name="product"') !== false || 
            strpos($form_content, 'name="price"') !== false) {
            // Adicionar apenas parâmetros especiais como key=1 que não existem no formulário
            $special_params = [];
            
            // Verificar se o parâmetro key existe na URL
            if (isset($_GET['key']) && strpos($form_content, 'name="key"') === false) {
                $special_params[] = '<input type="hidden" name="key" value="' . htmlspecialchars($_GET['key'], ENT_QUOTES, 'UTF-8') . '">';
            }
            
            // Se houver parâmetros especiais para adicionar
            if (!empty($special_params)) {
                $hidden_fields = implode("\n", $special_params);
                
                // Encontrar um bom local para inserir os campos
                $insert_pos = strrpos($form_content, '</input>');
                if ($insert_pos === false) {
                    $insert_pos = strrpos($form_content, '</button>');
                }
                
                if ($insert_pos !== false) {
                    // Inserir após o último input/button
                    $new_form_content = substr_replace($form_content, $hidden_fields, $insert_pos + 8, 0);
                    $new_form = str_replace($form_content, $new_form_content, $original_form);
                    $html = str_replace($original_form, $new_form, $html);
                }
            }
            
            continue; // Pula o restante do processamento para este formulário
        }
        
        // Prepara campos hidden para cada sub_id
        $hidden_fields = "";
        foreach ($sub_ids as $sub) {
            $name = $sub['name'];
            $val = "";
            if (isset($_GET[$name])) {
                $val = htmlspecialchars($_GET[$name], ENT_QUOTES, 'UTF-8');
            }
            
            // Verifica se o campo já existe no formulário
            if (strpos($form_content, 'name="' . $name . '"') === false) {
                $hidden_fields .= '<input type="hidden" name="' . $name . '" value="' . $val . '">';
            }
        }
        
        // Adicionar parâmetro key se existir na URL e ainda não existir no formulário
        if (isset($_GET['key']) && strpos($form_content, 'name="key"') === false) {
            $hidden_fields .= '<input type="hidden" name="key" value="' . htmlspecialchars($_GET['key'], ENT_QUOTES, 'UTF-8') . '">';
        }
        
        // Insere os campos ocultos após o último input ou antes do </form>
        $last_input_pos = strrpos($form_content, '</input>');
        $last_button_pos = strrpos($form_content, '</button>');
        
        if ($last_input_pos !== false && $last_input_pos > $last_button_pos) {
            // Insere após o último input
            $new_form_content = substr_replace($form_content, $hidden_fields, $last_input_pos + 8, 0);
        } elseif ($last_button_pos !== false) {
            // Insere após o último botão
            $new_form_content = substr_replace($form_content, $hidden_fields, $last_button_pos + 9, 0);
        } else {
            // Insere no início do formulário
            $new_form_content = $hidden_fields . $form_content;
        }
        
        // Substitui o formulário original pelo novo
        $new_form = str_replace($form_content, $new_form_content, $original_form);
        $html = str_replace($original_form, $new_form, $html);
    }
    
    return $html;
}

//переписываем все относительные src и href (не начинающиеся с http или с //)
function rewrite_relative_urls($html, $url)
{
    // Remover barras iniciais para garantir que os links sejam relativos
    if (substr($url, 0, 1) === '/') {
        $url = substr($url, 1);
    }
    
    // Garantir que a URL termine com uma barra
    if (substr($url, -1) !== '/' && !empty($url)) {
        $url .= '/';
    }
    
    // Corrigir links relativos em src, href e background-image
    $modified = preg_replace('/\ssrc=[\'\"](?!http|https|\/\/|data:)([^\'\"]+)[\'\"]/', " src=\"/$url\\1\"", $html);
    $modified = preg_replace('/\shref=[\'\"](?!http|https|mailto:|tel:|whatsapp:|#|\/\/)([^\'\"]+)[\'\"]/', " href=\"/$url\\1\"", $modified);
    $modified = preg_replace('/background-image:\s*url\([\'"]?(?!http|https|#|\/\/|data:)([^\)]+)[\'"]?\)/', "background-image: url(/$url\\1)", $modified);
    
    // Corrigir links para CSS e JS
    $modified = preg_replace('/<link[^>]+href=[\'\"](?!http|https|\/\/|data:)([^\'\"]+\.css)[\'\"][^>]*>/', "<link href=\"/$url\\1\" rel=\"stylesheet\">", $modified);
    $modified = preg_replace('/<script[^>]+src=[\'\"](?!http|https|\/\/|data:)([^\'\"]+\.js)[\'\"][^>]*><\/script>/', "<script src=\"/$url\\1\"></script>", $modified);
    
    // Corrigir links para fontes
    $modified = preg_replace('/url\([\'"]?(?!http|https|\/\/|data:)([^\'"\)]+\.(woff|woff2|ttf|eot))[\'"]?\)/', "url(/$url\\1)", $modified);
    
    return $modified;
}

function remove_scrapbook($html){
	$modified = preg_replace('/data\-scrapbook\-source=[\'\"][^\'\"]+[\'\"]/', '', $html);
	$modified = preg_replace('/data\-scrapbook\-create=[\'\"][^\'\"]+[\'\"]/', '', $modified);
    return $modified;
}

function remove_from_html($html,$filename){
    $remove_file_name=__DIR__.'/scripts/'.$filename;
    if (!file_exists($remove_file_name)) {
        echo 'File Not Found '.$remove_file_name;
        return $html;
    }
    $modified=$html;
    foreach(file($remove_file_name) as $line){
        $linetype=substr(trim($line), -3);
        $l=trim($line);
        switch($linetype){
            case '.js':
                $r="/<script.*?".$l.".*?script>/";
                $modified=preg_replace($r,'',$modified);
                break;
            case 'css':
                $r="/<link.*?rel=[\'\"]stylesheet.*?".$l."[^>]*>/";
                $modified=preg_replace($r,'',$modified);
                break;
            default:
                $modified=str_replace(trim($line),'',$modified);
                break;
        }
    }
    return $modified;
}

// Nova função para adicionar lazy loading de imagens
function add_lazy_load($html) {
    // Substitui atributos src por data-src e adiciona classe lazy
    $html = preg_replace('/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/is', '<img$1src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="$2"$3 class="lazy">', $html);
    
    // Adiciona script de lazy loading no final da página
    $lazyScript = '<script>document.addEventListener("DOMContentLoaded", function() {
        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
        
        if ("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.classList.remove("lazy");
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });
            
            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            // Fallback para navegadores que não suportam IntersectionObserver
            let active = false;
            
            const lazyLoad = function() {
                if (active === false) {
                    active = true;
                    
                    setTimeout(function() {
                        lazyImages.forEach(function(lazyImage) {
                            if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
                                lazyImage.src = lazyImage.dataset.src;
                                lazyImage.classList.remove("lazy");
                                
                                lazyImages = lazyImages.filter(function(image) {
                                    return image !== lazyImage;
                                });
                                
                                if (lazyImages.length === 0) {
                                    document.removeEventListener("scroll", lazyLoad);
                                    window.removeEventListener("resize", lazyLoad);
                                    window.removeEventListener("orientationchange", lazyLoad);
                                }
                            }
                        });
                        
                        active = false;
                    }, 200);
                }
            };
            
            document.addEventListener("scroll", lazyLoad);
            window.addEventListener("resize", lazyLoad);
            window.addEventListener("orientationchange", lazyLoad);
        }
    });</script>';
    
    return str_replace('</body>', $lazyScript . '</body>', $html);
}

// Funções de cache para conteúdo
function get_cached_content($url, $cache_duration = 3600) {
    // Se DEBUG_DISABLE_CACHE estiver ativado, sempre retorna false para forçar regeneração
    if (defined('DEBUG_DISABLE_CACHE') && DEBUG_DISABLE_CACHE === true) {
        return false;
    }
    
    $cache_dir = __DIR__ . '/cache';
    $cache_file = $cache_dir . '/' . md5($url) . '.html';
    
    // Criar diretório de cache se não existir
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }
    
    // Verificar se existe cache válido
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_duration)) {
        return file_get_contents($cache_file);
    }
    
    return false;
}

function save_cached_content($url, $content) {
    // Se DEBUG_DISABLE_CACHE estiver ativado, não salva o cache
    if (defined('DEBUG_DISABLE_CACHE') && DEBUG_DISABLE_CACHE === true) {
        return;
    }
    
    $cache_dir = __DIR__ . '/cache';
    $cache_file = $cache_dir . '/' . md5($url) . '.html';
    
    // Criar diretório de cache se não existir
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }
    
    file_put_contents($cache_file, $content);
}
?>
