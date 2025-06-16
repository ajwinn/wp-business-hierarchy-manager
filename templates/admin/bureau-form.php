<?php
/**
 * Bureau Company Form Template
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Add New Bureau Company</h1>
    <hr class="wp-header-end">
    
    <?php include plugin_dir_path(__FILE__) . 'partials/form-header.php'; ?>
    
    <form method="post" action="" id="bureau-company-form" class="space-y-8">
        <?php wp_nonce_field('business_hierarchy_manager_save_bureau', 'business_hierarchy_manager_bureau_nonce'); ?>
        
        <div class="grid gap-6">
            <?php include plugin_dir_path(__FILE__) . 'partials/bureau-info-card.php'; ?>
            <?php include plugin_dir_path(__FILE__) . 'partials/user-info-card.php'; ?>
        </div>
        
        <?php include plugin_dir_path(__FILE__) . 'partials/form-actions.php'; ?>
    </form>
</div> 