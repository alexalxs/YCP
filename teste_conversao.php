<?php
// Configurações básicas
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cabeçalhos
header('Content-Type: text/html; charset=utf-8');

// Verificar se o script de conversão existe
$script_exists = file_exists('scripts/conversion_tracker.js');
$script_content = $script_exists ? file_get_contents('scripts/conversion_tracker.js') : '';
$script_size = strlen($script_content);

// Verificar se o arquivo de log existe e tem permissões
$log_dir_exists = is_dir('logs');
$log_dir_writable = is_writable('logs');
$log_file_exists = file_exists('logs/email_conversions.log');
$log_file_writable = $log_file_exists ? is_writable('logs/email_conversions.log') : false;

// Verificar se o arquivo email_track.php existe
$track_file_exists = file_exists('email_track.php');
$track_file_content = $track_file_exists ? file_get_contents('email_track.php') : '';
$track_file_size = strlen($track_file_content);

// Exibir resultados
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste de Conversão</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { margin-bottom: 10px; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
    <h1>Teste de Conversão</h1>
    
    <div class="info">
        <strong>Script de conversão:</strong> 
        <span class="<?php echo $script_exists ? 'success' : 'error'; ?>">
            <?php echo $script_exists ? 'Existe' : 'Não existe'; ?>
        </span>
        (<?php echo $script_size; ?> bytes)
    </div>
    
    <div class="info">
        <strong>Diretório de logs:</strong> 
        <span class="<?php echo $log_dir_exists ? 'success' : 'error'; ?>">
            <?php echo $log_dir_exists ? 'Existe' : 'Não existe'; ?>
        </span>
        (<span class="<?php echo $log_dir_writable ? 'success' : 'error'; ?>">
            <?php echo $log_dir_writable ? 'Gravável' : 'Não gravável'; ?>
        </span>)
    </div>
    
    <div class="info">
        <strong>Arquivo de log:</strong> 
        <span class="<?php echo $log_file_exists ? 'success' : 'error'; ?>">
            <?php echo $log_file_exists ? 'Existe' : 'Não existe'; ?>
        </span>
        (<span class="<?php echo $log_file_writable ? 'success' : 'error'; ?>">
            <?php echo $log_file_writable ? 'Gravável' : 'Não gravável'; ?>
        </span>)
    </div>
    
    <div class="info">
        <strong>Arquivo de rastreamento:</strong> 
        <span class="<?php echo $track_file_exists ? 'success' : 'error'; ?>">
            <?php echo $track_file_exists ? 'Existe' : 'Não existe'; ?>
        </span>
        (<?php echo $track_file_size; ?> bytes)
    </div>
    
    <h2>Formulário de Teste</h2>
    <form id="testForm" action="#" method="post">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="teste@exemplo.com">
        </div>
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="Teste">
        </div>
        <div>
            <button type="submit">Enviar</button>
        </div>
    </form>
    
    <h2>Resultado do Envio</h2>
    <div id="result">Aguardando envio...</div>
    
    <script>
    // Adicionar o script de conversão
    <?php echo $script_content; ?>
    
    // Adicionar código para exibir o resultado do envio
    document.getElementById('testForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const formData = new FormData();
        formData.append('email', email);
        formData.append('oferta', 'teste');
        
        fetch('/email_track.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        })
        .catch(error => {
            document.getElementById('result').innerHTML = '<pre class="error">Erro: ' + error + '</pre>';
        });
    });
    </script>
</body>
</html> 