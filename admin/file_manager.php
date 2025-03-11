<?php
/**
 * Yellow Cloaker - Gerenciador de Arquivos
 * Permite criar pastas e fazer upload de arquivos para páginas white e ofertas
 */

// Incluir configurações
require_once '../settings.php';
require_once '../db.php';
require_once 'password.php';

// Verificar senha
global $log_password;
if (!isset($_GET['password']) || $_GET['password'] !== $log_password) {
    header('Location: /');
    exit;
}

// Definir diretórios base
$white_dir = '../' . $conf->get('white.customfolders.basedir', 'white');
$offers_dir = '../' . $conf->get('black.customfolders.basedir', 'offers');

// Processar ações
$message = '';
$error = '';

// Criar nova pasta
if (isset($_POST['action']) && $_POST['action'] == 'create_folder') {
    $folder_type = $_POST['folder_type'];
    $folder_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folder_name']);
    
    if (empty($folder_name)) {
        $error = "Nome da pasta inválido";
    } else {
        $base_dir = ($folder_type == 'white') ? $white_dir : $offers_dir;
        $folder_path = $base_dir . '/' . $folder_name;
        
        if (file_exists($folder_path)) {
            $error = "A pasta já existe";
        } else {
            if (mkdir($folder_path, 0755, true)) {
                // Criar arquivo index.html vazio
                file_put_contents($folder_path . '/index.html', '<html><body><h1>Página em branco</h1></body></html>');
                $message = "Pasta criada com sucesso";
            } else {
                $error = "Erro ao criar pasta";
            }
        }
    }
}

// Upload de arquivo
if (isset($_POST['action']) && $_POST['action'] == 'upload_file') {
    $folder_type = $_POST['folder_type'];
    $folder_name = $_POST['folder_name'];
    $base_dir = ($folder_type == 'white') ? $white_dir : $offers_dir;
    $target_dir = $base_dir . '/' . $folder_name . '/';
    
    if (!file_exists($target_dir)) {
        $error = "Pasta não existe";
    } else {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $target_file = $target_dir . basename($_FILES['file']['name']);
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                $message = "Arquivo enviado com sucesso";
            } else {
                $error = "Erro ao enviar arquivo";
            }
        } else {
            $error = "Erro no upload: " . $_FILES['file']['error'];
        }
    }
}

// Excluir pasta
if (isset($_POST['action']) && $_POST['action'] == 'delete_folder') {
    $folder_type = $_POST['folder_type'];
    $folder_name = $_POST['folder_name'];
    $base_dir = ($folder_type == 'white') ? $white_dir : $offers_dir;
    $folder_path = $base_dir . '/' . $folder_name;
    
    function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            return false;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
        return true;
    }
    
    if (deleteDir($folder_path)) {
        $message = "Pasta excluída com sucesso";
    } else {
        $error = "Erro ao excluir pasta";
    }
}

// Obter lista de pastas
function get_folders($dir) {
    $folders = [];
    
    if (file_exists($dir)) {
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if ($item != '.' && $item != '..' && is_dir($dir . '/' . $item)) {
                $folders[] = $item;
            }
        }
    }
    
    return $folders;
}

// Definir tipo selecionado
$selected_type = isset($_GET['type']) ? $_GET['type'] : 'white';

// Depuração
echo "<!-- Debug: white_dir = $white_dir -->";
echo "<!-- Debug: offers_dir = $offers_dir -->";
echo "<!-- Debug: selected_type = $selected_type -->";

$white_folders = get_folders($white_dir);
$offer_folders = get_folders($offers_dir);

// Depuração
if (!empty($white_folders)) {
    echo "<!-- Debug: white_folders = " . implode(", ", $white_folders) . " -->";
} else {
    echo "<!-- Debug: white_folders está vazio -->";
}

if (!empty($offer_folders)) {
    echo "<!-- Debug: offer_folders = " . implode(", ", $offer_folders) . " -->";
} else {
    echo "<!-- Debug: offer_folders está vazio -->";
}

// Obter arquivos de uma pasta
function get_files($dir, $folder) {
    $files = [];
    $path = $dir . '/' . $folder;
    
    if (file_exists($path)) {
        $items = scandir($path);
        
        foreach ($items as $item) {
            if ($item != '.' && $item != '..' && !is_dir($path . '/' . $item)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($path . '/' . $item),
                    'modified' => date("Y-m-d H:i:s", filemtime($path . '/' . $item))
                ];
            }
        }
    }
    
    return $files;
}

// Obter arquivos se uma pasta for selecionada
$selected_folder = '';
$files = [];

if (isset($_GET['folder']) && isset($_GET['type'])) {
    $selected_folder = $_GET['folder'];
    $base_dir = ($selected_type == 'white') ? $white_dir : $offers_dir;
    $files = get_files($base_dir, $selected_folder);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Arquivos - Yellow Cloaker</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dropzone {
            border: 2px dashed #ddd;
            border-radius: 5px;
            padding: 25px;
            text-align: center;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            cursor: pointer;
        }
        .dropzone.highlight {
            border-color: #007bff;
            background-color: #e9f5ff;
        }
        .file-list {
            width: 100%;
            border-collapse: collapse;
        }
        .file-list th, .file-list td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .file-list th {
            background-color: #f8f9fa;
        }
        .file-icon {
            margin-right: 10px;
            color: #6c757d;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gerenciador de Arquivos</h1>
            <a href="index.php?password=<?= $_GET['password'] ?>" class="btn btn-secondary">Voltar ao Painel</a>
        </div>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= $selected_type == 'white' ? 'active' : '' ?>" href="?password=<?= $_GET['password'] ?>&type=white">Páginas White</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= $selected_type == 'offers' ? 'active' : '' ?>" href="?password=<?= $_GET['password'] ?>&type=offers">Ofertas</a>
            </li>
        </ul>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Criar Nova Pasta</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <input type="hidden" name="action" value="create_folder">
                            <input type="hidden" name="folder_type" value="<?= $selected_type ?>">
                            
                            <div class="form-group">
                                <label for="folder_name">Nome da Pasta:</label>
                                <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                                <small class="form-text text-muted">Use apenas letras, números, traços e sublinhados.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Criar Pasta</button>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Pastas Existentes</h5>
                    </div>
                    <div class="card-body folder-list">
                        <?php
                        $folders = ($selected_type == 'white') ? $white_folders : $offer_folders;
                        
                        if (count($folders) > 0):
                        ?>
                            <div class="list-group">
                                <?php foreach ($folders as $folder): ?>
                                    <a href="?password=<?= $_GET['password'] ?>&type=<?= $selected_type ?>&folder=<?= $folder ?>" class="list-group-item list-group-item-action <?= $selected_folder == $folder ? 'active' : '' ?>">
                                        <i class="fas fa-folder"></i> <?= $folder ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Nenhuma pasta encontrada.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <?php if (!empty($selected_folder)): ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Gerenciar Pasta: <?= $selected_folder ?></h5>
                            <form method="post" action="" onsubmit="return confirm('Tem certeza que deseja excluir esta pasta? Esta ação não pode ser desfeita.');">
                                <input type="hidden" name="action" value="delete_folder">
                                <input type="hidden" name="folder_type" value="<?= $selected_type ?>">
                                <input type="hidden" name="folder_name" value="<?= $selected_folder ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Excluir Pasta</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <form method="post" action="" enctype="multipart/form-data" id="upload-form">
                                <input type="hidden" name="action" value="upload_file">
                                <input type="hidden" name="folder_type" value="<?= $selected_type ?>">
                                <input type="hidden" name="folder_name" value="<?= $selected_folder ?>">
                                
                                <div id="dropzone" class="dropzone">
                                    <input type="file" name="file" id="file-input" style="display: none;">
                                    <p>Arraste arquivos aqui ou clique para selecionar</p>
                                </div>
                                
                                <div class="progress mb-3" style="display: none;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                                
                                <div class="actions">
                                    <button type="submit" class="btn btn-primary">Enviar Arquivo</button>
                                </div>
                            </form>
                            
                            <h3>Arquivos</h3>
                            <?php if (empty($files)): ?>
                                <p>Nenhum arquivo encontrado</p>
                            <?php else: ?>
                                <table class="file-list">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Tamanho</th>
                                            <th>Modificado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($files as $file): ?>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-file file-icon"></i>
                                                    <?= $file['name'] ?>
                                                </td>
                                                <td><?= round($file['size'] / 1024, 2) ?> KB</td>
                                                <td><?= $file['modified'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <p>Selecione ou crie uma pasta para gerenciar arquivos.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('file-input');
            
            if (dropzone) {
                dropzone.addEventListener('click', () => {
                    fileInput.click();
                });
                
                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length > 0) {
                        document.getElementById('upload-form').submit();
                    }
                });
                
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('highlight');
                });
                
                dropzone.addEventListener('dragleave', () => {
                    dropzone.classList.remove('highlight');
                });
                
                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('highlight');
                    
                    if (e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        document.getElementById('upload-form').submit();
                    }
                });
            }
        });
    </script>
</body>
</html> 