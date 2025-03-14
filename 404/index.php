<?php
//Включение отладочной информации
ini_set('display_errors','1'); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
//Конец включения отладочной информации

require_once __DIR__.'/../bases/ipcountry.php';
$ip = getip();
$country = getcountry($ip);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>404</title>
 
</head>

<body>
  <div id="coordAnchor">
    <div id="top-cookie">
		<p>404 Page Not Found</p>
	
</body>
</html>
