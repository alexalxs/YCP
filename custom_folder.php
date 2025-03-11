<?php
// Configurações básicas
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar dependências necessárias
require_once 'settings.php';
require_once 'htmlprocessing.php';

// Verificar parâmetros
if (!isset($_GET['type']) || !isset($_GET['folder'])) {
    header('HTTP/1.0 400 Bad Request');
    echo 'Parâmetros inválidos';
    exit;
}

$type = $_GET['type'];
$folder = $_GET['folder'];

// Verificar tipo
if ($type !== 'white' && $type !== 'offers') {
    header('HTTP/1.0 400 Bad Request');
    echo 'Tipo inválido';
    exit;
}

// Verificar se a pasta existe
$folder_path = $type . '/' . $folder;
if (!file_exists($folder_path) || !is_dir($folder_path)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Pasta não encontrada';
    exit;
}

// Verificar se o arquivo index.html existe
$index_path = $folder_path . '/index.html';
if (!file_exists($index_path)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Arquivo index.html não encontrado';
    exit;
}

// Carregar o conteúdo do arquivo
$html = file_get_contents($index_path);

// Adicionar script de conversão de lead
if (file_exists('scripts/conversion_tracker.js')) {
    $script_content = file_get_contents('scripts/conversion_tracker.js');
    if (strpos($html, '</body>') !== false) {
        $html = str_replace('</body>', '<script>' . $script_content . '</script></body>', $html);
    } else {
        $html .= '<script>' . $script_content . '</script>';
    }
}

// Processar formulários para adicionar campo oculto de oferta
if ($type === 'offers') {
    $html = preg_replace('/<form([^>]*)>/i', '<form$1><input type="hidden" name="oferta" value="' . $folder . '">', $html);
}

// Reescrever URLs relativas
$baseurl = '/' . $folder_path . '/';
$html = rewrite_relative_urls($html, $baseurl);

// Enviar o conteúdo
header('Content-Type: text/html; charset=utf-8');
echo $html; 