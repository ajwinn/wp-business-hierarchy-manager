<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/includes
 */
class Business_Hierarchy_Manager_Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Set deactivation flag
        update_option('business_hierarchy_manager_deactivated', true);
        update_option('business_hierarchy_manager_deactivated_at', current_time('mysql'));

        // Flush rewrite rules
        flush_rewrite_rules();

        // Log deactivation
        self::log_deactivation();

        // Note: We don't remove database tables or roles on deactivation
        // This preserves user data and allows for reactivation without data loss
        // Tables and roles will only be removed on uninstall
    }

    /**
     * Log deactivation for debugging
     *
     * @since    1.0.0
     */
    private static function log_deactivation() {
        $log_data = array(
            'version' => BUSINESS_HIERARCHY_MANAGER_VERSION,
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
            'reason' => 'Plugin deactivated'
        );
        
        update_option('business_hierarchy_manager_deactivation_log', $log_data);
    }
}
