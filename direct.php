<?php
// Arquivo simples para redirecionamento direto
require_once 'config/ConfigInterface.php';
require_once 'config/AbstractConfig.php';
require_once 'config/Config.php';
require_once 'config/Parser/ParserInterface.php';
require_once 'config/Parser/Json.php';
require_once 'config/ErrorException.php';
require_once 'config/Exception.php';
require_once 'config/Exception/ParseException.php';
require_once 'config/Exception/FileNotFoundException.php';

use Noodlehaus\Config;
$conf = Config::load(__DIR__.'/settings.json');

// Verificar modo TDS
$tds_mode = $conf['tds.mode'];
echo "TDS Mode: " . $tds_mode . "<br>";

// Se TDS está desligado, redirecionar para a oferta
if ($tds_mode == 'off') {
    $black_landing_folder_names = $conf['black.landing.folder.names'];
    if (!empty($black_landing_folder_names)) {
        $landing = $black_landing_folder_names[0];
        echo "Redirecionando para: /" . $landing . "/<br>";
        echo "<script>window.location.href='/" . $landing . "/';</script>";
        header('Location: /'.$landing.'/');
        exit;
    }
}
// Se TDS está em modo full, redirecionar para a branca
else if ($tds_mode == 'full') {
    $white_folder_names = $conf['white.folder.names'];
    if (!empty($white_folder_names)) {
        $white = $white_folder_names[0];
        echo "Redirecionando para: /" . $white . "/<br>";
        echo "<script>window.location.href='/" . $white . "/';</script>";
        header('Location: /'.$white.'/');
        exit;
    }
}

// Caso contrário, mostrar a configuração
echo "<h2>Configuração atual:</h2>";
echo "<pre>";
var_dump($conf->all());
echo "</pre>";
?> 