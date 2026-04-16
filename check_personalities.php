<?php
// Diagnostic script to check personality filtering
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Models/Personality.php';

echo "<h2>Personality Diagnostic</h2>\n";

try {
    // Check all personalities
    $allPersonalities = \App\Models\Personality::all();
    echo "<h3>All Personalities in Database:</h3>\n";
    foreach ($allPersonalities as $p) {
        $status = $p['active'] ? 'ACTIVE' : 'INACTIVE';
        $default = $p['is_default'] ? ' (DEFAULT)' : '';
        $comingSoon = $p['coming_soon'] ? ' (COMING SOON)' : '';
        echo "- ID: {$p['id']} | {$p['name']} | {$status}{$default}{$comingSoon}<br>\n";
    }
    
    echo "<h3>Active Personalities (what users should see):</h3>\n";
    $activePersonalities = \App\Models\Personality::allVisibleForUsers();
    foreach ($activePersonalities as $p) {
        $default = $p['is_default'] ? ' (DEFAULT)' : '';
        $comingSoon = $p['coming_soon'] ? ' (COMING SOON)' : '';
        echo "- ID: {$p['id']} | {$p['name']}{$default}{$comingSoon}<br>\n";
    }
    
    if (empty($activePersonalities)) {
        echo "<strong>No active personalities found!</strong><br>\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>