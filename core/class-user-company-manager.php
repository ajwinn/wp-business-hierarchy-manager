<?php

/**
 * User Company Manager Class
 *
 * Handles user-company relationships
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/core
 */
class Business_Hierarchy_Manager_User_Company_Manager {

    /**
     * Initialize the class
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Initialize user company manager
    }

    /**
     * Assign user to company
     *
     * @since    1.0.0
     * @param    int    $user_id       User ID
     * @param    int    $company_id    Company ID
     * @param    string $company_type  Company type (bureau/client)
     * @return   bool                  Success status
     */
    public function assign_user_to_company($user_id, $company_id, $company_type) {
        // TODO: Implement user-company assignment
        update_user_meta($user_id, '_company_id', $company_id);
        update_user_meta($user_id, '_company_type', $company_type);
        return true;
    }

    /**
     * Get user's company
     *
     * @since    1.0.0
     * @param    int    $user_id    User ID
     * @return   array              Company data
     */
    public function get_user_company($user_id) {
        return array(
            'company_id' => get_user_meta($user_id, '_company_id', true),
            'company_type' => get_user_meta($user_id, '_company_type', true)
        );
    }

    /**
     * Get all users in a company
     *
     * @since    1.0.0
     * @param    int    $company_id    Company ID
     * @return   array                Array of user objects
     */
    public function get_company_users($company_id) {
        // TODO: Implement get company users
        return get_users(array(
            'meta_key' => '_company_id',
            'meta_value' => $company_id
        ));
    }

    /**
     * Check if user belongs to company
     *
     * @since    1.0.0
     * @param    int    $user_id       User ID
     * @param    int    $company_id    Company ID
     * @return   bool                  Whether user belongs to company
     */
    public function user_belongs_to_company($user_id, $company_id) {
        $user_company = get_user_meta($user_id, '_company_id', true);
        return $user_company == $company_id;
    }
}
