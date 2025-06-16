<?php
/**
 * Test Page Template
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Test Page</h1>
    <hr class="wp-header-end">
    
    <?php include plugin_dir_path(__FILE__) . 'partials/test-header.php'; ?>
    
    <div class="grid gap-6">
        <?php include plugin_dir_path(__FILE__) . 'partials/test-content.php'; ?>
    </div>
    
    <?php include plugin_dir_path(__FILE__) . 'partials/test-footer.php'; ?>
</div> 