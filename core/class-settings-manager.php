<?php

/**
 * Settings Manager Class
 *
 * Handles company settings
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/core
 */
class Business_Hierarchy_Manager_Settings_Manager {

    /**
     * Initialize the class
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Initialize settings manager
    }

    /**
     * Update company settings
     *
     * @since    1.0.0
     * @param    int    $company_id    Company ID
     * @param    array  $settings      Settings array
     * @return   bool                  Success status
     */
    public function update_company_settings($company_id, $settings) {
        // TODO: Implement settings update
        foreach ($settings as $key => $value) {
            update_post_meta($company_id, "_company_{$key}", $value);
        }
        return true;
    }

    /**
     * Get company settings
     *
     * @since    1.0.0
     * @param    int    $company_id    Company ID
     * @return   array                 Settings array
     */
    public function get_company_settings($company_id) {
        return array(
            'location' => get_post_meta($company_id, '_company_location', true),
            'primary_member' => get_post_meta($company_id, '_company_primary_member', true)
        );
    }

    /**
     * Set primary member
     *
     * @since    1.0.0
     * @param    int    $company_id    Company ID
     * @param    int    $user_id       User ID
     * @return   bool                  Success status
     */
    public function set_primary_member($company_id, $user_id) {
        update_post_meta($company_id, '_company_primary_member', $user_id);
        return true;
    }
}
