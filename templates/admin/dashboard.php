<?php
/**
 * Dashboard Template
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Data should be passed from the calling function
$version = isset($version) ? $version : '';
$tables_created = isset($tables_created) ? $tables_created : 'No';
$roles_created = isset($roles_created) ? $roles_created : 'No';
?>

<div class="wrap">
    <h1>Business Hierarchy Manager</h1>
    <p>Plugin is activated successfully!</p>
    <p>Version: <?php echo esc_html($version); ?></p>
    <p>Database tables created: <?php echo esc_html($tables_created); ?></p>
    <p>User roles created: <?php echo esc_html($roles_created); ?></p>
</div> 