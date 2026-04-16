<?php

// Script para atualizar as cores restantes em todos os arquivos
require_once __DIR__ . '/config/config.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relative);
    $file = __DIR__ . '/app/' . $relativePath . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Lista de arquivos para atualizar
$filesToUpdate = [
    'app/Views/courses/show.php',
    'app/Views/social/portfolio_view.php',
    'app/Views/certificates/show.php',
    'app/Views/social/community_create.php',
    // Adicione outros arquivos conforme necessário
];

// Função para substituir cores hardcoded por dinâmicas
function updateColorsInFile($filePath) {
    if (!file_exists($filePath)) {
        echo "Arquivo não encontrado: $filePath\n";
        return false;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Substituições principais
    $replacements = [
        // Gradiente principal
        'background:linear-gradient(135deg,#e53935,#ff6f60)' => 'background:<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        'linear-gradient(135deg,#e53935,#ff6f60)' => '<?= \\App\\Helpers\\ThemeHelper::getButtonGradient() ?>',
        
        // Cores individuais
        'color:#050509' => 'color:<?= \\App\\Helpers\\ThemeHelper::getBackground() ?>',
        'color:#ff6f60' => 'color:<?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
        'border:1px solid #ff6f60' => 'border:1px solid <?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
        'border-color:#ff6f60' => 'border-color:<?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
        
        // Cores de fundo
        'background:#e53935' => 'background:<?= \\App\\Helpers\\ThemeHelper::getPrimary() ?>',
        'background:#ff6f60' => 'background:<?= \\App\\Helpers\\ThemeHelper::getSecondary() ?>',
    ];
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "Atualizado: $filePath\n";
        return true;
    }
    
    echo "Nenhuma alteração necessária: $filePath\n";
    return false;
}

// Atualizar arquivos
foreach ($filesToUpdate as $file) {
    updateColorsInFile($file);
}

echo "Atualização de cores concluída!\n";