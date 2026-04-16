<?php
// Script to fix remaining red colors in admin views

$files_to_fix = [
    'app/Views/admin/personalidades/index.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
        'border:1px solid #ff6f60' => 'border:1px solid <?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
        'color:#ff6f60' => 'color:<?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
    ],
    'app/Views/admin/assinaturas/index.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
    ],
    'app/Views/admin/cursos/module_form.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
    ],
    'app/Views/admin/cursos/lessons.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
        'color:#ff6f60' => 'color:<?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
    ],
    'app/Views/admin/cursos/live_form.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
    ],
    'app/Views/admin/cursos/module_exam.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
    ],
    'app/Views/admin/cursos/lesson_form.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
    ],
    'app/Views/admin/cursos/modules.php' => [
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
        'color:#ff6f60' => 'color:<?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
    ],
];

echo "<h2>🎨 Corrigindo Cores Vermelhas Restantes</h2>\n";

foreach ($files_to_fix as $file => $replacements) {
    if (!file_exists($file)) {
        echo "❌ Arquivo não encontrado: {$file}<br>\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $original_content = $content;
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    if ($content !== $original_content) {
        file_put_contents($file, $content);
        echo "✅ Atualizado: {$file}<br>\n";
    } else {
        echo "⚪ Sem alterações: {$file}<br>\n";
    }
}

echo "<hr>\n";
echo "<h3>🎉 CORREÇÃO CONCLUÍDA!</h3>\n";
echo "<p>Todas as cores vermelhas foram substituídas por chamadas do ThemeHelper.</p>\n";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Execute a migração do banco de dados para aplicar as cores!</p>\n";
echo "<p><a href='/force_migration.php' style='color: #2196F3;'>🔧 Executar Migração</a></p>\n";
?>