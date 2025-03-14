<?php
/**
 * Script para limpar o cache do sistema
 * Use este script durante o desenvolvimento para forçar a regeneração do cache
 * Exemplo de uso: php clear_cache.php
 */

// Pasta de cache
$cache_dir = __DIR__ . '/cache';

// Verificar se a pasta existe
if (!is_dir($cache_dir)) {
    echo "Pasta de cache não existe: $cache_dir\n";
    exit(0);
}

// Contar arquivos antes
$before_count = count(glob("$cache_dir/*.html"));

// Limpar todos os arquivos de cache
$files = glob("$cache_dir/*.html");
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

// Contar arquivos depois
$after_count = count(glob("$cache_dir/*.html"));
$removed = $before_count - $after_count;

echo "Cache limpo com sucesso!\n";
echo "Arquivos removidos: $removed\n";
?> 