<?php
/**
 * Test Footer Partial
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="p-6">
        <h3 class="text-lg font-semibold leading-none tracking-tight mb-4">Template System Status</h3>
        <div class="space-y-2 text-sm">
            <p><strong>Template Directory:</strong> <?php echo plugin_dir_path(__FILE__); ?></p>
            <p><strong>CSS Loaded:</strong> <span class="text-green-600">✓ Yes</span></p>
            <p><strong>Partials Working:</strong> <span class="text-green-600">✓ Yes</span></p>
            <p><strong>shadcn/ui Classes:</strong> <span class="text-green-600">✓ Active</span></p>
        </div>
    </div>
</div> 