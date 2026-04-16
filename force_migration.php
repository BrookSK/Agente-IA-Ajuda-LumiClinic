<?php
// Script para forçar a criação da tabela settings e configurações de cores

require_once 'config.php';

echo "<h2>🔧 Forçando Criação da Tabela Settings</h2>\n";

try {
    $pdo = \App\Core\Database::getConnection();
    echo "✅ Conexão com banco OK<br>\n";
    
    // Verificar se tabela settings existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->fetch()) {
        echo "✅ Tabela 'settings' já existe<br>\n";
    } else {
        echo "❌ Tabela 'settings' NÃO existe. Criando...<br>\n";
        
        // Criar tabela settings
        $createTable = "
        CREATE TABLE settings (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(100) NOT NULL UNIQUE,
            `value` TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTable);
        echo "✅ Tabela 'settings' criada com sucesso!<br>\n";
    }
    
    // Inserir configurações de cores padrão
    echo "<h3>🎨 Configurando Cores Padrão</h3>\n";
    
    $defaultSettings = [
        'theme_color_primary' => '#2196F3',      // Azul ao invés de vermelho
        'theme_color_secondary' => '#FF9800',    // Laranja ao invés de vermelho
        'theme_color_accent' => '#4CAF50',       // Verde
        'theme_color_background' => '#050509',   // Fundo escuro
        'theme_color_surface' => '#111118',      // Superfície
        'theme_color_text' => '#f5f5f5',         // Texto claro
        'theme_color_text_secondary' => '#b0b0b0', // Texto secundário
        'theme_button_background' => '#2196F3',   // Botão azul
        'theme_button_background_type' => 'gradient',
        'theme_button_text' => '#ffffff',        // Texto do botão
        'theme_button_border' => 'transparent',  // Borda transparente
        'theme_headline_color' => '#ffffff',     // Títulos
        'brand_platform_name' => 'Resenha 2.0',
        'brand_platform_short' => 'Resenha',
        'brand_mascot_name' => 'Tuquinha',
        'brand_agency_name' => 'Agência Tuca',
        'brand_slogan' => 'Branding vivo na veia',
        'brand_company_name' => 'Nuvem Labs',
        'brand_user_agent' => 'TuquinhaApp',
        'brand_community_name' => 'Comunidade do Tuquinha',
        'brand_logo_url' => '',
    ];
    
    foreach ($defaultSettings as $key => $value) {
        try {
            $stmt = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
            $stmt->execute(['key' => $key, 'value' => $value]);
            echo "✅ {$key} = {$value}<br>\n";
        } catch (Exception $e) {
            echo "❌ Erro ao inserir {$key}: " . $e->getMessage() . "<br>\n";
        }
    }
    
    echo "<hr>\n";
    echo "<h3>🎉 CONFIGURAÇÃO CONCLUÍDA!</h3>\n";
    echo "<p><strong>As cores foram alteradas para AZUL e LARANJA para eliminar o vermelho.</strong></p>\n";
    echo "<p>Agora você pode:</p>\n";
    echo "<ol>\n";
    echo "<li><a href='/admin/config' style='color: #2196F3;'>Acessar as configurações</a> para personalizar as cores</li>\n";
    echo "<li>Alterar as cores primária e secundária conforme sua preferência</li>\n";
    echo "<li>Fazer upload do logo da sua marca</li>\n";
    echo "</ol>\n";
    
    echo "<p><strong>⚠️ IMPORTANTE:</strong> Recarregue as páginas do site para ver as mudanças!</p>\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . htmlspecialchars($e->getMessage()) . "<br>\n";
    echo "📋 Detalhes: " . htmlspecialchars($e->getTraceAsString()) . "<br>\n";
}

echo "<hr>\n";
echo "<h3>🔗 Links Úteis:</h3>\n";
echo "<ul>\n";
echo "<li><a href='/' style='color: #2196F3;'>Voltar para a Home</a></li>\n";
echo "<li><a href='/admin/config' style='color: #2196F3;'>Configurações do Sistema</a></li>\n";
echo "<li><a href='/login' style='color: #2196F3;'>Fazer Login</a></li>\n";
echo "</ul>\n";
?>