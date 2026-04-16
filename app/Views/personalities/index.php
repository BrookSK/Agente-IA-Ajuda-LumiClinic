<?php
/** @var array $personalities */
/** @var int|null $conversationId */

use App\Models\Setting;

$conversationId = isset($conversationId) ? (int)$conversationId : 0;
?>

<div style="max-width: 1000px; margin: 0 auto;">
    <h1 style="font-size: 26px; margin-bottom: 10px; font-weight: 650;">Escolha a personalidade do <?= htmlspecialchars(\App\Models\Branding::mascotName()) ?></h1>
    
    <div style="background: red; color: white; padding: 20px; margin: 20px 0; border-radius: 8px;">
        <h2>TESTE DE DEBUG</h2>
        <p><strong>Se você vê esta mensagem vermelha, a view está funcionando!</strong></p>
        <p>Personalities variable exists: <?= isset($personalities) ? 'SIM' : 'NÃO' ?></p>
        <p>Personalities count: <?= count($personalities ?? []) ?></p>
        <p>Is array: <?= is_array($personalities ?? null) ? 'SIM' : 'NÃO' ?></p>
        
        <?php if (isset($personalities) && is_array($personalities)): ?>
            <h3>Personalidades encontradas:</h3>
            <?php foreach ($personalities as $index => $p): ?>
                <div style="border: 1px solid white; padding: 5px; margin: 5px 0;">
                    <strong><?= $index + 1 ?>. <?= htmlspecialchars($p['name'] ?? 'Sem nome') ?></strong><br>
                    ID: <?= $p['id'] ?? 'N/A' ?><br>
                    Ativo: <?= isset($p['active']) ? ($p['active'] ? 'SIM' : 'NÃO') : 'INDEFINIDO' ?><br>
                    Área: <?= htmlspecialchars($p['area'] ?? 'N/A') ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><strong>Nenhuma personalidade encontrada ou variável não é um array!</strong></p>
        <?php endif; ?>
    </div>
    
    <p>Fim do teste</p>
</div>