<?php
// Arquivo para criar um lead diretamente
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'settings.php';
require_once 'db.php';

// Dados do lead
$subid = uniqid('test_');
$name = 'Cliente Teste Direto';
$phone = '11987654321';
$email = 'teste@exemplo.com';
$landing = 'offer1';

// Criar o lead com status inicial
$result = add_lead($subid, $name, $phone, 'Lead');

echo "Resultado da criação do lead: ";
var_dump($result);
echo "<br>";
echo "SubID gerado: " . $subid;
echo "<br><br>";

// Exibir informações úteis para criar um postback manualmente
echo "Para atualizar este lead para Purchase, use:<br>";
echo "curl -v \"http://localhost:8000/postback.php?subid={$subid}&status=Purchase&payout=99.99\"";
?> 