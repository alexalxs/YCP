<?php
/**
 * Sistema de Rastreamento de Conversões - Yellow Cloaker
 * Página de estatísticas de conversões de email
 */

// Definir constantes
define('LOG_PATH', 'logs');
define('CONVERSION_LOG', LOG_PATH . '/email_conversions.log');
define('STATS_PASSWORD', isset($log_password) ? $log_password : '12345');

// Verificar senha
$password = isset($_GET['password']) ? $_GET['password'] : '';
if ($password !== STATS_PASSWORD) {
    header('HTTP/1.0 403 Forbidden');
    echo '<h1>Acesso Negado</h1>';
    echo '<p>Você precisa fornecer a senha correta para acessar esta página.</p>';
    echo '<p>Exemplo: <code>email_stats.php?password=SUA_SENHA</code></p>';
    exit;
}

// Função para ler e analisar o arquivo de log
function parseLogFile($file) {
    $entries = [];
    
    if (!file_exists($file)) {
        return $entries;
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if ($data) {
            $entries[] = $data;
        }
    }
    
    return $entries;
}

// Obter dados
$conversions = parseLogFile(CONVERSION_LOG);

// Organizar dados por oferta
$stats_by_offer = [];
$stats_by_date = [];
$total_conversions = count($conversions);

foreach ($conversions as $conversion) {
    $oferta = isset($conversion['oferta']) ? $conversion['oferta'] : 'desconhecida';
    $date = isset($conversion['timestamp']) ? date('Y-m-d', strtotime($conversion['timestamp'])) : date('Y-m-d');
    
    // Estatísticas por oferta
    if (!isset($stats_by_offer[$oferta])) {
        $stats_by_offer[$oferta] = [
            'count' => 0,
            'emails' => []
        ];
    }
    
    $stats_by_offer[$oferta]['count']++;
    $stats_by_offer[$oferta]['emails'][] = $conversion['email'];
    
    // Estatísticas por data
    if (!isset($stats_by_date[$date])) {
        $stats_by_date[$date] = [
            'count' => 0,
            'offers' => []
        ];
    }
    
    $stats_by_date[$date]['count']++;
    
    if (!isset($stats_by_date[$date]['offers'][$oferta])) {
        $stats_by_date[$date]['offers'][$oferta] = 0;
    }
    
    $stats_by_date[$date]['offers'][$oferta]++;
}

// Ordenar dados
krsort($stats_by_date); // Ordenar datas em ordem decrescente

// Filtros
$filter_oferta = isset($_GET['oferta']) ? $_GET['oferta'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$filter_email = isset($_GET['email']) ? $_GET['email'] : '';

// Filtrar conversões se necessário
$filtered_conversions = $conversions;

if ($filter_oferta) {
    $filtered_conversions = array_filter($filtered_conversions, function($item) use ($filter_oferta) {
        return isset($item['oferta']) && $item['oferta'] == $filter_oferta;
    });
}

if ($filter_date) {
    $filtered_conversions = array_filter($filtered_conversions, function($item) use ($filter_date) {
        return isset($item['timestamp']) && strpos($item['timestamp'], $filter_date) === 0;
    });
}

if ($filter_email) {
    $filtered_conversions = array_filter($filtered_conversions, function($item) use ($filter_email) {
        return isset($item['email']) && strpos($item['email'], $filter_email) !== false;
    });
}

// Ordenar conversões por timestamp (mais recentes primeiro)
usort($filtered_conversions, function($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});

// Função para calcular a porcentagem
function percentage($count, $total) {
    if ($total == 0) return '0%';
    return round(($count / $total) * 100, 1) . '%';
}

// Geração de URL de navegação
function buildUrl($params = []) {
    global $filter_oferta, $filter_date, $filter_email;
    
    $current_params = [
        'password' => $_GET['password']
    ];
    
    if ($filter_oferta && !isset($params['oferta'])) {
        $current_params['oferta'] = $filter_oferta;
    }
    
    if ($filter_date && !isset($params['date'])) {
        $current_params['date'] = $filter_date;
    }
    
    if ($filter_email && !isset($params['email'])) {
        $current_params['email'] = $filter_email;
    }
    
    $merged_params = array_merge($current_params, $params);
    return '?' . http_build_query($merged_params);
}

// Cabeçalho HTML
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas de Conversão de Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        h1, h2, h3 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .filters {
            background-color: #eee;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters input, .filters select {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .filters button {
            padding: 8px 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filters button:hover {
            background-color: #1a252f;
        }
        .stats-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 0.9em;
            text-transform: uppercase;
        }
        .chart-container {
            margin: 30px 0;
            height: 300px;
        }
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .pagination li {
            margin-right: 5px;
        }
        .pagination a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            background-color: #f2f2f2;
            color: #333;
            border-radius: 4px;
        }
        .pagination a:hover,
        .pagination a.active {
            background-color: #2c3e50;
            color: #fff;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 0.8em;
            border-radius: 10px;
            background-color: #3498db;
            color: white;
        }
        .badge-success {
            background-color: #2ecc71;
        }
        .no-data {
            text-align: center;
            padding: 50px;
            color: #777;
            font-style: italic;
        }
        .clear-filter {
            text-decoration: none;
            color: #e74c3c;
            font-size: 0.9em;
            margin-left: 5px;
        }
        .clear-filter:hover {
            text-decoration: underline;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Estatísticas de Conversão de Email</h1>
        
        <!-- Filtros -->
        <div class="filters">
            <form method="get" action="">
                <input type="hidden" name="password" value="<?php echo htmlspecialchars($_GET['password']); ?>">
                
                <label for="oferta">Oferta:</label>
                <select name="oferta" id="oferta">
                    <option value="">Todas as ofertas</option>
                    <?php foreach ($stats_by_offer as $oferta => $data): ?>
                        <option value="<?php echo htmlspecialchars($oferta); ?>" <?php echo $filter_oferta == $oferta ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($oferta); ?> (<?php echo $data['count']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="date">Data:</label>
                <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($filter_date); ?>">
                
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" placeholder="Filtrar por email" value="<?php echo htmlspecialchars($filter_email); ?>">
                
                <button type="submit">Filtrar</button>
                <?php if ($filter_oferta || $filter_date || $filter_email): ?>
                    <a href="?password=<?php echo htmlspecialchars($_GET['password']); ?>" class="clear-filter">Limpar filtros</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Resumo estatístico -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total de Conversões</div>
                <div class="stat-value"><?php echo $total_conversions; ?></div>
            </div>
            
            <div class="stat-box">
                <div class="stat-label">Ofertas Diferentes</div>
                <div class="stat-value"><?php echo count($stats_by_offer); ?></div>
            </div>
            
            <div class="stat-box">
                <div class="stat-label">Conversões Filtradas</div>
                <div class="stat-value"><?php echo count($filtered_conversions); ?></div>
            </div>
            
            <div class="stat-box">
                <div class="stat-label">Última Conversão</div>
                <div class="stat-value"><?php echo $total_conversions > 0 ? date('d/m', strtotime($conversions[0]['timestamp'])) : '-'; ?></div>
            </div>
        </div>
        
        <!-- Gráfico por Oferta -->
        <?php if (count($stats_by_offer) > 0): ?>
            <div class="stats-card">
                <h2>Conversões por Oferta</h2>
                <div class="chart-container">
                    <canvas id="ofertasChart"></canvas>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Gráfico por Data -->
        <?php if (count($stats_by_date) > 0): ?>
            <div class="stats-card">
                <h2>Conversões por Data</h2>
                <div class="chart-container">
                    <canvas id="datasChart"></canvas>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Tabela de Ofertas -->
        <div class="stats-card">
            <h2>Desempenho por Oferta</h2>
            <?php if (count($stats_by_offer) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Oferta</th>
                            <th>Conversões</th>
                            <th>Porcentagem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats_by_offer as $oferta => $data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($oferta); ?></td>
                                <td><?php echo $data['count']; ?></td>
                                <td><?php echo percentage($data['count'], $total_conversions); ?></td>
                                <td>
                                    <a href="<?php echo buildUrl(['oferta' => $oferta]); ?>">Filtrar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">Nenhum dado disponível</div>
            <?php endif; ?>
        </div>
        
        <!-- Tabela de Conversões -->
        <div class="stats-card">
            <h2>Lista de Conversões<?php echo $filter_oferta ? ' - Oferta: ' . htmlspecialchars($filter_oferta) : ''; ?></h2>
            <?php if (count($filtered_conversions) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Email</th>
                            <th>Oferta</th>
                            <th>IP</th>
                            <th>Referência</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filtered_conversions as $conversion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($conversion['timestamp']); ?></td>
                                <td><?php echo htmlspecialchars($conversion['email']); ?></td>
                                <td>
                                    <span class="badge"><?php echo htmlspecialchars($conversion['oferta']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($conversion['ip']); ?></td>
                                <td><?php echo htmlspecialchars(isset($conversion['referrer']) ? $conversion['referrer'] : '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">Nenhum dado disponível</div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Configurar Gráfico de Ofertas
        <?php if (count($stats_by_offer) > 0): ?>
        const ofertasCtx = document.getElementById('ofertasChart').getContext('2d');
        new Chart(ofertasCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo "'" . implode("', '", array_keys($stats_by_offer)) . "'"; ?>],
                datasets: [{
                    label: 'Conversões',
                    data: [<?php echo implode(', ', array_column($stats_by_offer, 'count')); ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Conversões por Oferta'
                    }
                }
            }
        });
        <?php endif; ?>
        
        // Configurar Gráfico por Data
        <?php if (count($stats_by_date) > 0): ?>
        const datasCtx = document.getElementById('datasChart').getContext('2d');
        new Chart(datasCtx, {
            type: 'line',
            data: {
                labels: [<?php echo "'" . implode("', '", array_keys($stats_by_date)) . "'"; ?>],
                datasets: [{
                    label: 'Conversões',
                    data: [<?php echo implode(', ', array_column($stats_by_date, 'count')); ?>],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Tendência de Conversões por Data'
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html> 