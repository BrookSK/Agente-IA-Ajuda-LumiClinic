<?php
// Script para testar se o logo e as configurações estão funcionando
require_once 'config.php';

echo "<!DOCTYPE html><html><head><title>Teste Logo e Configurações</title></head><body>";
echo "<h2>🔧 Teste de Logo e Configurações</h2>";

try {
    // Testar se a tabela settings existe
    $pdo = \App\Core\Database::getConnection();
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->fetch()) {
        echo "✅ Tabela 'settings' existe<br>";
        
        // Testar configurações de cores
        $primaryColor = \App\Models\Setting::get('theme_color_primary', 'NOT_FOUND');
        $secondaryColor = \App\Models\Setting::get('theme_color_secondary', 'NOT_FOUND');
        $logoUrl = \App\Models\Setting::get('brand_logo_url', 'NOT_FOUND');
        
        echo "<h3>🎨 Configurações de Cores:</h3>";
        echo "Cor Primária: <span style='color: {$primaryColor};'>■</span> {$primaryColor}<br>";
        echo "Cor Secundária: <span style='color: {$secondaryColor};'>■</span> {$secondaryColor}<br>";
        
        echo "<h3>🖼️ Configuração de Logo:</h3>";
        echo "URL do Logo: " . ($logoUrl !== 'NOT_FOUND' ? $logoUrl : 'Não configurado') . "<br>";
        
        // Testar ThemeHelper
        echo "<h3>🎯 ThemeHelper:</h3>";
        $helperPrimary = \App\Helpers\ThemeHelper::getPrimary();
        $helperSecondary = \App\Helpers\ThemeHelper::getSecondary();
        $helperGradient = \App\Helpers\ThemeHelper::getButtonGradient();
        
        echo "ThemeHelper Primária: <span style='color: {$helperPrimary};'>■</span> {$helperPrimary}<br>";
        echo "ThemeHelper Secundária: <span style='color: {$helperSecondary};'>■</span> {$helperSecondary}<br>";
        echo "ThemeHelper Gradiente: {$helperGradient}<br>";
        
        // Testar Branding
        echo "<h3>🏷️ Branding:</h3>";
        $brandingLogo = \App\Models\Branding::logoUrl();
        echo "Branding Logo URL: " . ($brandingLogo !== '' ? $brandingLogo : 'Não configurado') . "<br>";
        
        if ($brandingLogo !== '') {
            echo "<div style='margin-top:10px;'>";
            echo "<strong>Pré-visualização do Logo:</strong><br>";
            echo "<img src='{$brandingLogo}' alt='Logo' style='max-height:60px; max-width:200px; object-fit:contain; border:1px solid #ccc; margin-top:5px;'>";
            echo "</div>";
        }
        
        // Verificar se as cores são diferentes de vermelho
        if ($primaryColor === '#e53935' || $secondaryColor === '#ff6f60') {
            echo "<div style='background:#ffebee; border:1px solid #f44336; padding:10px; margin:10px 0; border-radius:5px;'>";
            echo "⚠️ <strong>ATENÇÃO:</strong> As cores ainda estão vermelhas! Execute a migração primeiro.";
            echo "</div>";
        } else {
            echo "<div style='background:#e8f5e8; border:1px solid #4caf50; padding:10px; margin:10px 0; border-radius:5px;'>";
            echo "✅ <strong>SUCESSO:</strong> As cores foram alteradas para azul/laranja!";
            echo "</div>";
        }
        
    } else {
        echo "❌ Tabela 'settings' NÃO existe<br>";
        echo "<div style='background:#ffebee; border:1px solid #f44336; padding:10px; margin:10px 0; border-radius:5px;'>";
        echo "⚠️ <strong>ERRO:</strong> A tabela settings não existe. Execute a migração primeiro!<br>";
        echo "<a href='/run_migration.php' style='color:#1976d2;'>Executar Migração</a>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . htmlspecialchars($e->getMessage()) . "<br>";
}

echo "<hr>";
echo "<h3>🔗 Links Úteis:</h3>";
echo "<ul>";
echo "<li><a href='/run_migration.php' style='color:#1976d2;'>Executar Migração</a></li>";
echo "<li><a href='/admin/config' style='color:#1976d2;'>Configurações do Sistema</a></li>";
echo "<li><a href='/' style='color:#1976d2;'>Voltar para a Home</a></li>";
echo "</ul>";

echo "</body></html>";
?>