<?php
// Simple web interface to run the migration
require_once 'config.php';

echo "<!DOCTYPE html><html><head><title>Run Migration</title></head><body>";
echo "<h2>🔧 Executar Migração do Sistema</h2>";

if (isset($_GET['run']) && $_GET['run'] === 'yes') {
    echo "<h3>Executando migração...</h3>";
    
    // Redirect to the official migration endpoint
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $migrateUrl = $scheme . $host . '/migrate/run?key=tuq-migrate-2026';
    
    echo "<p>Redirecionando para: <a href='{$migrateUrl}'>{$migrateUrl}</a></p>";
    echo "<script>window.location.href = '{$migrateUrl}';</script>";
} else {
    echo "<p>Esta migração irá:</p>";
    echo "<ul>";
    echo "<li>✅ Criar a tabela 'settings' se não existir</li>";
    echo "<li>🎨 Inserir cores padrão AZUL e LARANJA (ao invés de vermelho)</li>";
    echo "<li>🏷️ Configurar informações de branding padrão</li>";
    echo "<li>📝 Permitir personalização via painel admin</li>";
    echo "</ul>";
    
    echo "<p><strong>⚠️ IMPORTANTE:</strong> Isso irá substituir as cores vermelhas por azul e laranja.</p>";
    
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='?run=yes' style='background: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>🚀 Executar Migração</a>";
    echo "</div>";
    
    echo "<h3>🔗 Links Úteis:</h3>";
    echo "<ul>";
    echo "<li><a href='/'>Voltar para a Home</a></li>";
    echo "<li><a href='/admin/config'>Configurações do Sistema (após migração)</a></li>";
    echo "<li><a href='/login'>Fazer Login</a></li>";
    echo "</ul>";
}

echo "</body></html>";
?>