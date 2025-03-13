<?php
function ywbsetcookie($name,$value,$path='/'){
	$expires = time()+60*60*24*5; //время, на которое ставятся куки, по умолчанию - 5 дней
	
	// Log para depuração
	if (defined('DEBUG_LOG') && DEBUG_LOG) {
		error_log("Definindo cookie: $name=$value, Path=$path, Expires=$expires");
		error_log("Headers já enviados antes do setcookie: " . (headers_sent() ? 'SIM' : 'NÃO'));
	}
	
	// Usar setcookie nativo do PHP para garantir compatibilidade
	if (!headers_sent()) {
		setcookie($name, $value, $expires, $path, '', true, false);
		
		// Também enviar o header manualmente como backup
		header("Set-Cookie: {$name}={$value}; Expires={$expires}; Path={$path}; SameSite=None; Secure", false);
	} else {
		// Se os headers já foram enviados, armazenar o cookie na sessão para uso futuro
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start(['read_and_close' => false]);
		}
		$_SESSION[$name] = $value;
		session_write_close();
		
		if (defined('DEBUG_LOG') && DEBUG_LOG) {
			error_log("Headers já enviados, cookie armazenado na sessão: $name=$value");
		}
	}
	
	// Definir também na superglobal $_COOKIE para uso imediato
	$_COOKIE[$name] = $value;
	
	// Log para depuração
	if (defined('DEBUG_LOG') && DEBUG_LOG) {
		error_log("Headers já enviados após o setcookie: " . (headers_sent() ? 'SIM' : 'NÃO'));
	}
}

function get_cookie($name){
	if (session_status()!==PHP_SESSION_ACTIVE) {
		session_start(['read_and_close'=>true]);
    }
    $c=(isset($_COOKIE[$name])?$_COOKIE[$name]:(isset($_SESSION[$name])?$_SESSION[$name]:''));
	return $c;
}

function get_subid(){
    $subid=get_cookie('subid');
	return $subid;
}

function set_subid(){
	if (session_status()!==PHP_SESSION_ACTIVE) {
		ini_set("session.cookie_secure", 1);
		session_start();
    }
    //устанавливаем пользователю в куки уникальный subid, либо берём его из куки, если он уже есть
    $cursubid=isset($_COOKIE['subid'])?$_COOKIE['subid']:uniqid();
    ywbsetcookie('subid',$cursubid,'/');
	$_SESSION['subid']=$cursubid;
	session_write_close();
    return $cursubid;
}

function set_facebook_cookies(){
	global $fbpixel_subname;
	if (isset($_GET[$fbpixel_subname]) && $_GET[$fbpixel_subname]!='')
		ywbsetcookie($fbpixel_subname,$_GET[$fbpixel_subname],'/');
	if (isset($_GET['fbclid']) && $_GET['fbclid']!='')
		ywbsetcookie('fbclid',$_GET['fbclid'],'/');
}

//проверяем, если у пользователя установлена куки, что он уже конвертился, а также имя и телефон, то сверяем время
//если прошло менее суток, то хуй ему, а не лид, обнуляем время
function has_conversion_cookies($name,$phone){
	$date = new DateTime();
	$ts = $date->getTimestamp();
	$is_duplicate=false;
	$cname = isset($_COOKIE['name'])?$_COOKIE['name']:'';
	$cphone = isset($_COOKIE['phone'])?$_COOKIE['phone']:'';
	$ctime = isset($_COOKIE['ctime'])?$_COOKIE['ctime']:'';
	
	if (!empty($ctime)&&!empty($name)&&!empty($phone)){
		if ($cname===$name&&$cphone===$phone){
			$secondsDiff = $ts - $ctime;
			if ($secondsDiff<24*60*60)
			{
				$is_duplicate=true;
				ywbsetcookie('ctime',$ts);
			}
		}
	}
	return $is_duplicate;
}
?>