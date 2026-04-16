<?php
/** @var array $personalities */
/** @var int|null $conversationId */

use App\Models\Setting;

$conversationId = isset($conversationId) ? (int)$conversationId : 0;
?>
<style>
    .persona-card {
        width: 300px;
        background: var(--surface-card);
        border-radius: 20px;
        border: 1px solid var(--border-subtle);
        overflow: hidden;
        color: var(--text-primary);
        text-decoration: none;
        box-shadow: 0 18px 35px rgba(0,0,0,0.25);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, opacity 0.18s ease, filter 0.18s ease;
        opacity: 0.85;
        transform: scale(0.98);
    }
    .persona-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 22px 40px rgba(15,23,42,0.3);
        border-color: var(--accent-soft);
        opacity: 1;
    }
    .persona-card-image {
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: var(--surface-subtle);
    }
    .persona-card-desc {
        font-size: 12px;
        color: var(--text-secondary);
        line-height: 1.4;
        max-height: 5.4em;
        overflow: hidden;
    }
    .persona-card-muted {
        font-size: 12px;
        color: var(--text-secondary);
    }
</style>

<div style="max-width: 1000px; margin: 0 auto;">
    <h1 style="font-size: 26px; margin-bottom: 10px; font-weight: 650;">Escolha a personalidade do <?= htmlspecialchars(\App\Models\Branding::mascotName()) ?></h1>
    <p style="color:var(--text-secondary); font-size: 14px; margin-bottom: 20px; max-width: 640px;">
        Cada personalidade é um "modo" diferente do <?= htmlspecialchars(\App\Models\Branding::mascotName()) ?>, com foco, jeito de falar e especialidade próprios.
        Escolha quem vai te ajudar neste próximo chat.
    </p>

    <?php if (empty($personalities)): ?>
        <div style="background:#111118; border-radius:12px; padding:12px 14px; border:1px solid #272727; font-size:14px; color:#b0b0b0; margin-top:12px;">
            Ainda não há personalidades ativas cadastradas pelo administrador.
            <br><br>
            <a href="/chat?new=1" style="display:inline-flex; align-items:center; gap:6px; margin-top:4px; border-radius:999px; padding:7px 12px; background:<?= \App\Helpers\ThemeHelper::getButtonGradient() ?>; color:<?= \App\Helpers\ThemeHelper::getBackground() ?>; font-size:13px; font-weight:600; text-decoration:none;">
                <span>Ir para o chat</span>
                <span>➤</span>
            </a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 30px;">
            <?php foreach ($personalities as $persona): ?>
                <?php
                    $id = (int)($persona['id'] ?? 0);
                    $name = trim((string)($persona['name'] ?? ''));
                    $area = trim((string)($persona['area'] ?? ''));
                    $imagePath = trim((string)($persona['image_path'] ?? ''));
                    $isDefault = !empty($persona['is_default']);
                    $isComingSoon = !empty($persona['coming_soon']);
                    $prompt = trim((string)($persona['prompt'] ?? ''));
                    
                    // Create description from prompt
                    $desc = '';
                    if ($prompt !== '') {
                        $basePrompt = $prompt;
                        $marker = 'Regras principais:';
                        $posMarker = stripos($basePrompt, $marker);
                        if ($posMarker !== false) {
                            $basePrompt = substr($basePrompt, 0, $posMarker);
                        }
                        $desc = substr($basePrompt, 0, 150);
                        if (strlen($basePrompt) > 150) {
                            $desc .= '...';
                        }
                    }
                    
                    if ($imagePath === '') {
                        $imagePath = '/public/favicon.png';
                    }
                ?>
                
                <?php if ($conversationId > 0): ?>
                    <form action="/chat/persona" method="post" style="margin:0;">
                        <input type="hidden" name="conversation_id" value="<?= (int)$conversationId ?>">
                        <input type="hidden" name="persona_id" value="<?= $id ?>">
                        <button type="submit" class="persona-card" <?= $isComingSoon ? 'disabled' : '' ?> style="
                            width:100%;
                            padding:0;
                            text-align:left;
                            cursor:<?= $isComingSoon ? 'not-allowed' : 'pointer' ?>;
                            border: none;
                            background: none;
                        ">
                <?php else: ?>
                    <a href="<?= $isComingSoon ? 'javascript:void(0)' : ('/chat?new=1&amp;persona_id=' . $id) ?>" class="persona-card" style="
                        cursor:<?= $isComingSoon ? 'not-allowed' : 'pointer' ?>;
                        pointer-events:<?= $isComingSoon ? 'none' : 'auto' ?>;
                    ">
                <?php endif; ?>
                
                    <div class="persona-card-image">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($name) ?>" onerror="this.onerror=null;this.src='/public/favicon.png';" style="width:100%; height:100%; object-fit:cover; display:block;">
                    </div>
                    <div style="padding:15px 18px 18px 18px;">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:6px;">
                            <div style="font-size:18px; font-weight:650; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?= htmlspecialchars($name) ?>
                            </div>
                            <div style="display:flex; gap:6px; align-items:center; flex-shrink:0;">
                                <?php if ($isComingSoon): ?>
                                    <span style="font-size:9px; text-transform:uppercase; letter-spacing:0.14em; border-radius:999px; padding:2px 7px; background:#201216; color:#ffcc80; border:1px solid #ff6f60;">Em breve</span>
                                <?php endif; ?>
                                <?php if ($isDefault): ?>
                                    <span style="font-size:9px; text-transform:uppercase; letter-spacing:0.14em; border-radius:999px; padding:2px 7px; background:#201216; color:#ffcc80; border:1px solid #ff6f60;">Principal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($area !== ''): ?>
                            <div style="font-size:13px; color:<?= \App\Helpers\ThemeHelper::getPrimary() ?>; margin-bottom:8px; font-weight:500;">
                                <?= htmlspecialchars($area) ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($desc !== ''): ?>
                            <div class="persona-card-desc">
                                <?= nl2br(htmlspecialchars($desc)) ?>
                            </div>
                        <?php else: ?>
                            <div class="persona-card-muted">
                                <?= $isComingSoon ? 'Preview disponível. Em breve você poderá usar essa personalidade.' : 'Clique para começar um chat com essa personalidade.' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php if ($conversationId > 0): ?>
                        </button>
                    </form>
                <?php else: ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>