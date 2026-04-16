<?php
// Temporary diagnostic page to check personalities
require_once __DIR__ . '/config/config.php';

try {
    global $currentDbConfig;
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $currentDbConfig['host'], $currentDbConfig['port'],
        $currentDbConfig['database'], $currentDbConfig['charset']);
    $pdo = new PDO($dsn, $currentDbConfig['username'], $currentDbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "<h2>🔍 Diagnóstico de Personalidades</h2>\n";
    
    // Check if personalities table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'personalities'");
    $stmt->execute();
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "<p>❌ Tabela 'personalities' não existe!</p>\n";
        exit;
    }
    
    echo "<p>✅ Tabela 'personalities' existe</p>\n";
    
    // Get table structure
    $stmt = $pdo->query("DESCRIBE personalities");
    $columns = $stmt->fetchAll();
    
    echo "<h3>📋 Estrutura da Tabela:</h3>\n";
    echo "<ul>\n";
    foreach ($columns as $col) {
        echo "<li>{$col['Field']} ({$col['Type']}) - {$col['Null']} - {$col['Key']}</li>\n";
    }
    echo "</ul>\n";
    
    // Get all personalities
    $stmt = $pdo->query('SELECT * FROM personalities ORDER BY id');
    $personalities = $stmt->fetchAll();
    
    echo "<h3>👥 Personalidades no Banco:</h3>\n";
    echo "<p>Total: " . count($personalities) . "</p>\n";
    
    if (empty($personalities)) {
        echo "<p>❌ Nenhuma personalidade encontrada no banco de dados!</p>\n";
        echo "<p>Você precisa criar personalidades em <a href='/admin/personalidades'>/admin/personalidades</a></p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>\n";
        echo "<tr><th>ID</th><th>Nome</th><th>Área</th><th>Ativo</th><th>Padrão</th><th>Em Breve</th><th>Slug</th></tr>\n";
        
        foreach ($personalities as $p) {
            $active = $p['active'] ? '✅' : '❌';
            $default = $p['is_default'] ? '⭐' : '';
            $comingSoon = $p['coming_soon'] ? '🚧' : '';
            
            echo "<tr>";
            echo "<td>{$p['id']}</td>";
            echo "<td>" . htmlspecialchars($p['name']) . "</td>";
            echo "<td>" . htmlspecialchars($p['area'] ?? '') . "</td>";
            echo "<td>{$active}</td>";
            echo "<td>{$default}</td>";
            echo "<td>{$comingSoon}</td>";
            echo "<td>" . htmlspecialchars($p['slug'] ?? '') . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
        
        // Count active personalities
        $activeCount = 0;
        foreach ($personalities as $p) {
            if ($p['active']) $activeCount++;
        }
        
        echo "<h3>📊 Resumo:</h3>\n";
        echo "<ul>\n";
        echo "<li>Total de personalidades: " . count($personalities) . "</li>\n";
        echo "<li>Personalidades ativas: {$activeCount}</li>\n";
        echo "<li>Personalidades inativas: " . (count($personalities) - $activeCount) . "</li>\n";
        echo "</ul>\n";
        
        if ($activeCount === 0) {
            echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; border-radius: 5px; color: #c62828;'>\n";
            echo "<strong>⚠️ PROBLEMA ENCONTRADO:</strong><br>\n";
            echo "Não há personalidades ativas! Você precisa ativar pelo menos uma personalidade em <a href='/admin/personalidades'>/admin/personalidades</a>\n";
            echo "</div>\n";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>

<p><a href="/personalidades">← Voltar para seleção de personalidades</a></p>
<p><a href="/admin/personalidades">🔧 Gerenciar personalidades (Admin)</a></p>