<?php
require_once 'url.php';

function redirect($url, $redirect_type = 302, $add_querystring = true)
{
    // Limpar o buffer de saída
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Garantir que não há saída antes do redirecionamento
    if (headers_sent($filename, $linenum)) {
        // Se os headers já foram enviados, usar JavaScript
        echo "<script type='text/javascript'>window.location.href='$url';</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=$url'></noscript>";
        echo "Se você não for redirecionado automaticamente, <a href='$url'>clique aqui</a>.";
        exit;
    }
    
    if ($add_querystring === true) {
        $url = add_querystring($url);
    }
    
    // Forçar protocolo correto e manter o caminho relativo ao diretório atual
    if (strpos($url, 'http') !== 0 && isset($_SERVER['HTTP_HOST'])) {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        
        // Obter o diretório atual relativo à raiz
        $current_dir = dirname($_SERVER['PHP_SELF']);
        if ($current_dir == '/') $current_dir = '';
        
        // Se a URL começa com '/', usar caminho absoluto
        if (strpos($url, '/') === 0) {
            $url = $protocol . $host . $url;
        } 
        // Se é um caminho relativo, manter relativo ao diretório atual
        else {
            $url = $protocol . $host . $current_dir . '/' . $url;
        }
    }
    
    // Limpar barras duplas no caminho (exceto após protocolo)
    $url = preg_replace('#(?<!:)//+#', '/', $url);
    
    // Definir headers para redirecionamento
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('X-Robots-Tag: noindex, nofollow');
    
    if ($redirect_type === 302) {
        header('Location: ' . $url);
    } else {
        header('Location: ' . $url, true, $redirect_type);
    }
    exit; // Garantir que o script para aqui
}

function jsredirect($url)
{
    echo "<script type='text/javascript'> window.location.href='$url';</script>";
    echo "<noscript><meta http-equiv='refresh' content='0;url=$url'></noscript>";
    echo "Se você não for redirecionado automaticamente, <a href='$url'>clique aqui</a>.";
    exit;
}
