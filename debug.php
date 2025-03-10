<?php
// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Função para mostrar informações de debug
function debug_info($message, $data = null) {
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 5px; border: 1px solid #ccc;'>";
    echo "<strong>$message</strong>";
    if ($data !== null) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
    echo "</div>";
}

// Informações do servidor
echo "<h2>Informações de Depuração</h2>";
debug_info("SERVER", $_SERVER);
debug_info("GET", $_GET);
debug_info("POST", $_POST);
debug_info("COOKIE", $_COOKIE);

// Testar caminhos de arquivos
$paths = [
    'branca/index.html' => file_exists('branca/index.html'),
    'branca/index.php' => file_exists('branca/index.php'),
    'oferta/index.html' => file_exists('oferta/index.html'),
    'oferta/index.php' => file_exists('oferta/index.php')
];
debug_info("Caminhos de Arquivos", $paths);

// Testar leitura de conteúdo
try {
    $branca_content = file_get_contents('branca/index.html');
    debug_info("Conteúdo branca/index.html existe", !empty($branca_content));
    debug_info("Tamanho branca/index.html", strlen($branca_content));
    
    $oferta_content = file_get_contents('oferta/index.html');
    debug_info("Conteúdo oferta/index.html existe", !empty($oferta_content));
    debug_info("Tamanho oferta/index.html", strlen($oferta_content));
} catch (Exception $e) {
    debug_info("Erro ao ler arquivos", $e->getMessage());
}

// Testar settings.json
try {
    $settings = json_decode(file_get_contents('settings.json'), true);
    debug_info("Settings carregado com sucesso", !empty($settings));
    debug_info("TDS mode", $settings['tds']['mode']);
    debug_info("White action", $settings['white']['action']);
    debug_info("Black action", $settings['black']['landing']['action']);
} catch (Exception $e) {
    debug_info("Erro ao ler settings.json", $e->getMessage());
}

// Verificação do buffer de saída
debug_info("Output buffer status", ob_get_status());
?> 