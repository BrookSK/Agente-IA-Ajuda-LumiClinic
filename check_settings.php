<?php
// Script para verificar e configurar as cores do tema

require_once 'config.php';

echo "<h2>🔍 Verificando Configurações de Cores</h2>\n";

try {
    $pdo = \App\Core\Database::getConnection();
    echo "✅ Conexão com banco OK<br>\n";
    
    // Verificar se tabela settings existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->fetch()) {
        echo "✅ Tabela 'settings' existe<br>\n";
        
        // Verificar configurações de cores atuais
        $colorSettings = [
            'theme_color_primary',
            'theme_color_secondary', 
            'theme_button_background'
        ];
        
        echo "<h3>🎨 Configurações Atuais:</h3>\n";
        foreach ($colorSettings as $key) {
            $stmt = $pdo->prepare('SELECT `value` FROM settings WHERE `key` = :key');
            $stmt->execute(['key' => $key]);
            $row = $stmt->fetch();
            $value = $row ? $row['value'] : 'NÃO DEFINIDO';
            echo "• {$key}: <span style='color: {$value};'>■</span> {$value}<br>\n";
        }
        
        // Inserir/atualizar configurações de cores para azul/laranja
        echo "<h3>🔧 Atualizando Cores para Azul/Laranja:</h3>\n";
        
        $newColors = [
            'theme_color_primary' => '#2196F3',      // Azul
            'theme_color_secondary' => '#FF9800',    // Laranja
            'theme_color_accent' => '#4CAF50',       // Verde
            'theme_button_background' => '#2196F3',   // Botão azul
            'theme_button_background_type' => 'gradient',
            'theme_button_text' => '#ffffff',
            'theme_button_border' => 'transparent',
            'theme_headline_color' => '#ffffff',
            'theme_color_background' => '#050509',
            'theme_color_surface' => '#111118',
            'theme_color_text' => '#f5f5f5',
            'theme_color_text_secondary' => '#b0b0b0',
        ];
        
        foreach ($newColors as $key => $value) {
            $stmt = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
            $stmt->execute(['key' => $key, 'value' => $value]);
            echo "✅ {$key} = <span style='color: {$value};'>■</span> {$value}<br>\n";
        }
        
        echo "<hr>\n";
        echo "<h3>🎉 CORES ATUALIZADAS!</h3>\n";
        echo "<p><strong>Todas as cores vermelhas foram substituídas por azul e laranja.</strong></p>\n";
        echo "<p>⚠️ <strong>Recarregue as páginas do site</strong> para ver as mudanças!</p>\n";
        
    } else {
        echo "❌ Tabela 'settings' NÃO existe<br>\n";
        echo "<p>Execute a migração primeiro: <a href='/migrate/run?key=tuq-migrate-2026'>Executar Migração</a></p>\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

echo "<hr>\n";
echo "<h3>🔗 Links:</h3>\n";
echo "<ul>\n";
echo "<li><a href='/' style='color: #2196F3;'>Voltar para a Home</a></li>\n";
echo "<li><a href='/admin/config' style='color: #2196F3;'>Configurações do Sistema</a></li>\n";
echo "<li><a href='/login' style='color: #2196F3;'>Fazer Login</a></li>\n";
echo "</ul>\n";
?>