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

// Handle form submission
if ($_POST && isset($_POST['business_hierarchy_manager_bureau_nonce']) && wp_verify_nonce($_POST['business_hierarchy_manager_bureau_nonce'], 'business_hierarchy_manager_save_bureau')) {
    // Process form submission
    $bureau_name = sanitize_text_field($_POST['post_title']);
    $street1 = sanitize_text_field($_POST['bureau_street1']);
    $street2 = sanitize_text_field($_POST['bureau_street2']);
    $city = sanitize_text_field($_POST['bureau_city']);
    $state = sanitize_text_field($_POST['bureau_state']);
    $zip = sanitize_text_field($_POST['bureau_zip']);
    $phone = sanitize_text_field($_POST['bureau_phone']);
    $primary_first_name = sanitize_text_field($_POST['primary_first_name']);
    $primary_last_name = sanitize_text_field($_POST['primary_last_name']);
    $primary_email = sanitize_email($_POST['primary_email']);
    
    // Create the bureau post
    $post_data = array(
        'post_title' => $bureau_name,
        'post_type' => 'bureau_company',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    );
    
    $post_id = wp_insert_post($post_data);
    
    if ($post_id && !is_wp_error($post_id)) {
        // Save meta fields
        update_post_meta($post_id, '_bureau_street1', $street1);
        update_post_meta($post_id, '_bureau_street2', $street2);
        update_post_meta($post_id, '_bureau_city', $city);
        update_post_meta($post_id, '_bureau_state', $state);
        update_post_meta($post_id, '_bureau_zip', $zip);
        update_post_meta($post_id, '_bureau_phone', $phone);
        update_post_meta($post_id, '_primary_first_name', $primary_first_name);
        update_post_meta($post_id, '_primary_last_name', $primary_last_name);
        update_post_meta($post_id, '_primary_email', $primary_email);
        
        // Redirect to success
        wp_redirect(admin_url('admin.php?page=business-hierarchy-manager&bureau_created=1'));
        exit;
    }
}
?>

<div class="w-full max-w-6xl px-8 py-8 bg-base-100 rounded-2xl shadow-xl border border-base-200">
    <h1 class="text-3xl font-bold mb-2 text-left">Add New Bureau Company</h1>
    <div class="alert alert-info mb-8 text-left">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>
        <span>Add a new payroll bureau to your system.</span>
    </div>
    <form method="post" action="" id="bureau-company-form" class="space-y-8 w-full">
        <?php wp_nonce_field('business_hierarchy_manager_save_bureau', 'business_hierarchy_manager_bureau_nonce'); ?>
        <div class="flex flex-row gap-8 items-start w-full">
            <div class="flex-1 min-w-0">
                <?php include plugin_dir_path(__FILE__) . 'partials/bureau-info-card.php'; ?>
            </div>
            <div class="flex-1 min-w-[400px]">
                <?php include plugin_dir_path(__FILE__) . 'partials/user-info-card.php'; ?>
            </div>
        </div>
        <?php include plugin_dir_path(__FILE__) . 'partials/form-actions.php'; ?>
    </form>
</div> 