<?php
// Script para atualizar as cores do sistema para azul/laranja

// Incluir configuração do banco
require_once 'config.php';

echo "🎨 Atualizando cores do sistema...\n";

try {
    $pdo = \App\Core\Database::getConnection();
    
    // Configurações de cores azul/laranja
    $colors = [
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
    
    foreach ($colors as $key => $value) {
        $stmt = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
        $stmt->execute(['key' => $key, 'value' => $value]);
        echo "✅ {$key} = {$value}\n";
    }
    
    echo "\n🎉 Cores atualizadas com sucesso!\n";
    echo "Recarregue as páginas do site para ver as mudanças.\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
}
?>