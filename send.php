<?php
//Включение отладочной информации
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Конец включения отладочной информации

require_once 'settings.php';
require_once 'db.php';
require_once 'cookies.php';
require_once 'redirect.php';
require_once 'requestfunc.php';

// Habilitar CORS para permitir solicitações do formulário
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Log para depuração
if (defined('DEBUG_LOG') && DEBUG_LOG) {
    error_log("==== INÍCIO DO PROCESSAMENTO SEND.PHP ====");
    error_log("Método: " . $_SERVER['REQUEST_METHOD']);
    error_log("Dados: " . print_r($_POST, true));
}

// Lidar com solicitações OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verifica se é uma ação de captura de email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'email') {
    // Verificar e processar o email
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }

    $email = $_POST['email'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $subid = isset($_POST['subid']) ? $_POST['subid'] : (isset($_COOKIE['subid']) ? $_COOKIE['subid'] : '');

    if (empty($subid)) {
        // Se não tiver subid, gerar um novo
        $subid = md5(uniqid(rand(), true));
        ywbsetcookie('subid', $subid, '/');
    }

    // Log para depuração
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("Processando email: $email para subid: $subid");
        error_log("Nome do usuário: $name");
    }

    // Registrar o lead no banco de dados (incluindo o nome)
    // Usar o nome padrão se não for fornecido
    $user_name = !empty($name) ? $name : 'Lead de Email';
    
    // Registrar o lead (com um número de telefone fictício para compatibilidade)
    $phone = '00000000000'; // Telefone fictício para manter compatibilidade
    add_lead($subid, $user_name, $phone, 'Lead');
    
    // Registrar o email no banco de dados
    add_email($subid, $email);
    
    // Enviar para o webhook do Autonami via proxy
    $webhook_url = 'https://dekoola.com/wp-json/autonami/v1/webhook/?bwfan_autonami_webhook_id=10&bwfan_autonami_webhook_key=92c39df617252d128219dba772cff29a';
    
    // Preparar os dados para envio
    $webhook_data = array(
        'email' => $email,
        'name' => $user_name
    );
    
    // Adicionar outros parâmetros da URL original
    foreach ($_POST as $key => $value) {
        if ($key != 'action' && $key != 'email' && $key != 'name' && $key != 'subid') {
            $webhook_data[$key] = $value;
        }
    }
    
    // Log para depuração
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("Enviando dados para webhook: " . print_r($webhook_data, true));
    }
    
    // Fazer a requisição para o webhook
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($webhook_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $webhook_response = curl_exec($ch);
    $webhook_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Log da resposta do webhook
    if (defined('DEBUG_LOG') && DEBUG_LOG) {
        error_log("Resposta do webhook (status $webhook_status): $webhook_response");
    }

    // Responder com sucesso
    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'Lead registrado com sucesso',
        'webhook_status' => $webhook_status
    ]);
    exit;
}

// Processamento de leads normais
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar parâmetros necessários
    if (!isset($_POST['name']) || !isset($_POST['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Parâmetros obrigatórios ausentes']);
        exit;
    }

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $subid = isset($_POST['subid']) ? $_POST['subid'] : (isset($_COOKIE['subid']) ? $_COOKIE['subid'] : '');

    if (empty($subid)) {
        // Se não tiver subid, gerar um novo
        $subid = md5(uniqid(rand(), true));
        ywbsetcookie('subid', $subid, '/');
    }

    // Registrar o lead no banco de dados
    add_lead($subid, $name, $phone);

    // Se tiver email, registrá-lo também
    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        add_email($subid, $_POST['email']);
    }

    // Redirecionamento após o formulário ser processado
    if (isset($_POST['oferta'])) {
        $landing = $_POST['oferta'];
        if (file_exists(__DIR__ . '/' . $landing . '/thankyou.html')) {
            header('Location: /' . $landing . '/thankyou.html');
            exit;
        }
    }

    // Responder com sucesso se não houver redirecionamento
    echo json_encode(['success' => true, 'message' => 'Lead registrado com sucesso']);
    exit;
}

// Se não for um método suportado
http_response_code(405);
echo json_encode(['error' => 'Método não permitido']);

$name = '';
if (isset($_POST['name']))
    $name=$_POST['name'];
else if (isset($_POST['fio']))
    $name=$_POST['fio'];
else if (isset($_POST['first_name'])&&isset($_POST['last_name']))
    $name = $_POST['first_name'].' '.$_POST['last_name'];
else if (isset($_POST['firstname'])&&isset($_POST['lastname']))
    $name = $_POST['firstname'].' '.$_POST['lastname'];

$phone='';
if (isset($_POST['phone']))
    $phone=$_POST['phone'];
else if (isset($_POST['tel']))
    $phone=$_POST['tel'];

$subid = get_subid();
if ($subid==='' && isset($_POST['subid']))
    $subid=$_POST['subid'];

//если юзверь каким-то чудом отправил пустые поля в форме
if ($name===''||$phone===''){
    redirect('thankyou/thankyou.php?nopixel=1');
    return;
}

$date = new DateTime();
$ts = $date->getTimestamp();

$is_duplicate=has_conversion_cookies($name,$phone);
//устанавливаем пользователю в куки его имя и телефон, чтобы показать их на стр Спасибо
//также ставим куки даты конверсии
ywbsetcookie('name',$name,'/');
ywbsetcookie('phone',$phone,'/');
ywbsetcookie('ctime',$ts,'/');

// Verificar se é uma solicitação com key=1 - neste caso, processar sem tentar acessar order.php
if (isset($_GET['key']) && $_GET['key'] == '1') {
    // Adicionar o lead ao banco de dados sem tentar enviar para outro endpoint
    add_lead($subid, $name, $phone);
    
    // Coletar outros dados do formulário
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $product = isset($_POST['product']) ? $_POST['product'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $oferta = isset($_POST['oferta']) ? $_POST['oferta'] : '';
    
    // Salvar dados adicionais (se disponíveis)
    if (!empty($email)) ywbsetcookie('email', $email, '/');
    if (!empty($address)) ywbsetcookie('address', $address, '/');
    if (!empty($product)) ywbsetcookie('product', $product, '/');
    if (!empty($price)) ywbsetcookie('price', $price, '/');
    if (!empty($oferta)) ywbsetcookie('oferta', $oferta, '/');
    
    // Redirecionar para a página de agradecimento
    redirect('thankyou/thankyou.php?' . http_build_query($_GET));
    exit;
}

//шлём в ПП только если это не дубль
if (!$is_duplicate){
    $fullpath='';
    //если у формы прописан в action адрес, а не локальный скрипт, то шлём все данные формы на этот адрес
    if (substr($black_land_conversion_script, 0, 4 ) === "http"){
        $fullpath=$black_land_conversion_script;
    }
    //иначе составляем полный адрес до скрипта отправки ПП
    else{
        $url= get_cookie('landing').'/'.$black_land_conversion_script;
        $fullpath = get_abs_from_rel($url);
    }

    //на всякий случай, перед отправкой чекаем, установлен ли subid
    $sub_rewrites=array_column($sub_ids,'rewrite','name');
    if (array_key_exists('subid',$sub_rewrites)){
        if (!isset($_POST[$sub_rewrites['subid']])||
            $_POST[$sub_rewrites['subid']]!==$subid)
            $_POST[$sub_rewrites['subid']]=$subid;
    }

    $res=post($fullpath,http_build_query($_POST));

    //в ответе должен быть редирект, если его нет - грузим обычную страницу Спасибо кло
    switch($res["info"]["http_code"]){
        case 302:
            add_lead($subid,$name,$phone);
            if ($black_land_use_custom_thankyou_page ){
                redirect("thankyou/thankyou.php?".http_build_query($_GET),302,false);
            }
            else{
                redirect($res["info"]["redirect_url"]);
            }
            break;
        case 200:
            add_lead($subid,$name,$phone);
            if ($black_land_use_custom_thankyou_page ){
                jsredirect("thankyou/thankyou.php?".http_build_query($_GET));
            }
            else{
                echo $res["html"];
            }
            break;
        default:
            var_dump($res["error"]);
            var_dump($res["info"]);
            exit();
            break;
    }
}
else
{
    redirect('thankyou/thankyou.php?nopixel=1');
}

?>