<?php
// Diagnóstico das configurações de cores

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🔍 Diagnóstico do Sistema de Cores</h2>\n";

try {
    // Testar ThemeHelper
    echo "<h3>1. Teste do ThemeHelper:</h3>\n";
    
    if (class_exists('App\\Helpers\\ThemeHelper')) {
        $primary = \App\Helpers\ThemeHelper::getPrimary();
        $secondary = \App\Helpers\ThemeHelper::getSecondary();
        $buttonBg = \App\Helpers\ThemeHelper::getColor('button_background');
        
        echo "• Cor primária: <span style='color: {$primary}; font-weight: bold;'>■ {$primary}</span><br>\n";
        echo "• Cor secundária: <span style='color: {$secondary}; font-weight: bold;'>■ {$secondary}</span><br>\n";
        echo "• Cor do botão: <span style='color: {$buttonBg}; font-weight: bold;'>■ {$buttonBg}</span><br>\n";
        
        if ($primary === '#2196F3' && $secondary === '#FF9800') {
            echo "<p style='color: #4CAF50;'>✅ <strong>ThemeHelper está retornando cores azul/laranja!</strong></p>\n";
        } else {
            echo "<p style='color: #f44336;'>❌ <strong>ThemeHelper ainda está retornando cores antigas</strong></p>\n";
        }
    } else {
        echo "<p style='color: #f44336;'>❌ ThemeHelper não encontrado</p>\n";
    }
    
    // Testar conexão com banco
    echo "<h3>2. Teste da Conexão com Banco:</h3>\n";
    $pdo = \App\Core\Database::getConnection();
    echo "✅ Conexão OK<br>\n";
    
    // Verificar tabela settings
    echo "<h3>3. Verificação da Tabela Settings:</h3>\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->fetch()) {
        echo "✅ Tabela 'settings' existe<br>\n";
        
        // Verificar configurações de cores
        echo "<h3>4. Configurações de Cores no Banco:</h3>\n";
        $colorKeys = [
            'theme_color_primary',
            'theme_color_secondary', 
            'theme_button_background'
        ];
        
        $hasColors = false;
        foreach ($colorKeys as $key) {
            $stmt = $pdo->prepare('SELECT `value` FROM settings WHERE `key` = :key');
            $stmt->execute(['key' => $key]);
            $row = $stmt->fetch();
            
            if ($row) {
                $value = $row['value'];
                echo "• {$key}: <span style='color: {$value}; font-weight: bold;'>■ {$value}</span><br>\n";
                $hasColors = true;
            } else {
                echo "• {$key}: <span style='color: #f44336;'>NÃO DEFINIDO</span><br>\n";
            }
        }
        
        if (!$hasColors) {
            echo "<h3>5. 🔧 Inserindo Configurações de Cores:</h3>\n";
            
            $colors = [
                'theme_color_primary' => '#2196F3',
                'theme_color_secondary' => '#FF9800',
                'theme_color_accent' => '#4CAF50',
                'theme_button_background' => '#2196F3',
                'theme_button_background_type' => 'gradient',
                'theme_button_text' => '#ffffff',
                'theme_button_border' => 'transparent',
                'theme_headline_color' => '#ffffff',
                'theme_color_background' => '#050509',
                'theme_color_surface' => '#111118',
                'theme_color_text' => '#f5f5f5',
                'theme_color_text_secondary' => '#b0b0b0',
            ];
            
            foreach ($colors as $key => $value) {
                $stmt = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
                $stmt->execute(['key' => $key, 'value' => $value]);
                echo "✅ {$key} = <span style='color: {$value}; font-weight: bold;'>■ {$value}</span><br>\n";
            }
            
            echo "<p style='color: #4CAF50;'><strong>🎉 Cores inseridas com sucesso!</strong></p>\n";
            
            // Limpar cache do ThemeHelper
            if (class_exists('App\\Helpers\\ThemeHelper')) {
                \App\Helpers\ThemeHelper::clearCache();
                echo "<p>🔄 Cache do ThemeHelper limpo</p>\n";
            }
        }
        
    } else {
        echo "❌ Tabela 'settings' NÃO existe<br>\n";
    }
    
} catch (Exception $e) {
    echo "<p style='color: #f44336;'>❌ ERRO: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "<hr>\n";
echo "<h3>🔗 Próximos Passos:</h3>\n";
echo "<ol>\n";
echo "<li>Recarregue as páginas do site para ver as mudanças</li>\n";
echo "<li><a href='/' style='color: #2196F3;'>Testar a Home</a></li>\n";
echo "<li><a href='/login' style='color: #2196F3;'>Testar o Login</a></li>\n";
echo "<li><a href='/admin/config' style='color: #2196F3;'>Acessar Configurações Admin</a></li>\n";
echo "</ol>\n";
?>