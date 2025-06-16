<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/includes
 */
class Business_Hierarchy_Manager_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Set activation flag
        add_option('business_hierarchy_manager_activated', true);
        add_option('business_hierarchy_manager_version', BUSINESS_HIERARCHY_MANAGER_VERSION);

        // Create database tables
        self::create_database_tables();

        // Create default user roles
        self::create_default_roles();

        // Set default capabilities
        self::set_default_capabilities();

        // Flush rewrite rules for custom post types
        flush_rewrite_rules();

        // Log activation
        self::log_activation();
    }

    /**
     * Create necessary database tables
     *
     * @since    1.0.0
     */
    private static function create_database_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Table for company relationships
        $table_company_relationships = $wpdb->prefix . 'business_hierarchy_company_relationships';
        
        $sql_company_relationships = "CREATE TABLE $table_company_relationships (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            company_id bigint(20) NOT NULL,
            company_type varchar(20) NOT NULL,
            parent_company_id bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY company_id (company_id),
            KEY company_type (company_type),
            KEY parent_company_id (parent_company_id)
        ) $charset_collate;";

        // Table for user company assignments
        $table_user_companies = $wpdb->prefix . 'business_hierarchy_user_companies';
        
        $sql_user_companies = "CREATE TABLE $table_user_companies (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            company_id bigint(20) NOT NULL,
            company_type varchar(20) NOT NULL,
            role varchar(50) NOT NULL,
            is_primary tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_company (user_id, company_id),
            KEY user_id (user_id),
            KEY company_id (company_id),
            KEY company_type (company_type)
        ) $charset_collate;";

        // Table for invitations
        $table_invitations = $wpdb->prefix . 'business_hierarchy_invitations';
        
        $sql_invitations = "CREATE TABLE $table_invitations (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            company_id bigint(20) NOT NULL,
            company_type varchar(20) NOT NULL,
            role varchar(50) NOT NULL,
            token varchar(64) NOT NULL,
            status varchar(20) DEFAULT 'pending',
            invited_by bigint(20) NOT NULL,
            expires_at datetime DEFAULT NULL,
            accepted_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY token (token),
            KEY email (email),
            KEY company_id (company_id),
            KEY status (status),
            KEY expires_at (expires_at)
        ) $charset_collate;";

        // Table for onboarding records
        $table_onboardings = $wpdb->prefix . 'business_hierarchy_onboardings';
        
        $sql_onboardings = "CREATE TABLE $table_onboardings (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            company_id bigint(20) NOT NULL,
            invitation_id mediumint(9) DEFAULT NULL,
            onboarding_data longtext,
            status varchar(20) DEFAULT 'pending',
            completed_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY company_id (company_id),
            KEY invitation_id (invitation_id),
            KEY status (status)
        ) $charset_collate;";

        // Table for company settings
        $table_company_settings = $wpdb->prefix . 'business_hierarchy_company_settings';
        
        $sql_company_settings = "CREATE TABLE $table_company_settings (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            company_id bigint(20) NOT NULL,
            setting_key varchar(100) NOT NULL,
            setting_value longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY company_setting (company_id, setting_key),
            KEY company_id (company_id),
            KEY setting_key (setting_key)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql_company_relationships);
        dbDelta($sql_user_companies);
        dbDelta($sql_invitations);
        dbDelta($sql_onboardings);
        dbDelta($sql_company_settings);

        // Store table names in options for easy access
        update_option('business_hierarchy_manager_tables', array(
            'company_relationships' => $table_company_relationships,
            'user_companies' => $table_user_companies,
            'invitations' => $table_invitations,
            'onboardings' => $table_onboardings,
            'company_settings' => $table_company_settings
        ));
    }

    /**
     * Create default user roles
     *
     * @since    1.0.0
     */
    private static function create_default_roles() {
        // Bureau roles
        add_role('bureau_primary', __('Bureau Primary Member', 'business-hierarchy-manager'), array(
            'read' => true,
            'edit_bureau_company' => true,
            'edit_bureau_companies' => true,
            'read_bureau_company' => true,
            'read_bureau_companies' => true,
            'delete_bureau_company' => true,
            'delete_bureau_companies' => true,
            'edit_users' => true,
            'delete_users' => true,
            'create_users' => true,
            'edit_client_companies' => true,
            'edit_client_company' => true,
            'read_client_companies' => true,
            'read_client_company' => true,
            'delete_client_companies' => true,
            'delete_client_company' => true,
            'create_client_companies' => true,
            'edit_invitations' => true,
            'edit_invitation' => true,
            'read_invitations' => true,
            'read_invitation' => true,
            'delete_invitations' => true,
            'delete_invitation' => true,
            'send_invitations' => true,
            'manage_bureau_team' => true
        ));
        
        add_role('bureau_member', __('Bureau Team Member', 'business-hierarchy-manager'), array(
            'read' => true,
            'read_bureau_company' => true,
            'read_bureau_companies' => true,
            'edit_client_companies' => true,
            'edit_client_company' => true,
            'read_client_companies' => true,
            'read_client_company' => true,
            'delete_client_companies' => true,
            'delete_client_company' => true,
            'create_client_companies' => true,
            'edit_invitations' => true,
            'edit_invitation' => true,
            'read_invitations' => true,
            'read_invitation' => true,
            'delete_invitations' => true,
            'delete_invitation' => true,
            'send_invitations' => true
        ));
        
        // Client roles
        add_role('client_primary', __('Client Primary Member', 'business-hierarchy-manager'), array(
            'read' => true,
            'read_client_company' => true,
            'edit_client_company' => true,
            'edit_users' => true,
            'delete_users' => true,
            'create_users' => true,
            'edit_invitations' => true,
            'edit_invitation' => true,
            'read_invitations' => true,
            'read_invitation' => true,
            'delete_invitations' => true,
            'delete_invitation' => true,
            'send_invitations' => true,
            'manage_client_team' => true
        ));
        
        add_role('client_member', __('Client Team Member', 'business-hierarchy-manager'), array(
            'read' => true,
            'read_client_company' => true,
            'read_invitations' => true,
            'read_invitation' => true,
            'edit_invitations' => true,
            'edit_invitation' => true,
            'delete_invitations' => true,
            'delete_invitation' => true,
            'send_invitations' => true
        ));
    }

    /**
     * Set default capabilities for existing roles
     *
     * @since    1.0.0
     */
    private static function set_default_capabilities() {
        // Add capabilities to administrator role
        $admin_role = get_role('administrator');
        if ($admin_role) {
            // Bureau capabilities
            $admin_role->add_cap('edit_bureau_company');
            $admin_role->add_cap('edit_bureau_companies');
            $admin_role->add_cap('read_bureau_company');
            $admin_role->add_cap('read_bureau_companies');
            $admin_role->add_cap('delete_bureau_company');
            $admin_role->add_cap('delete_bureau_companies');
            
            // Client capabilities
            $admin_role->add_cap('edit_client_company');
            $admin_role->add_cap('edit_client_companies');
            $admin_role->add_cap('read_client_company');
            $admin_role->add_cap('read_client_companies');
            $admin_role->add_cap('delete_client_company');
            $admin_role->add_cap('delete_client_companies');
            $admin_role->add_cap('create_client_companies');
            
            // Invitation capabilities
            $admin_role->add_cap('edit_invitation');
            $admin_role->add_cap('edit_invitations');
            $admin_role->add_cap('read_invitation');
            $admin_role->add_cap('read_invitations');
            $admin_role->add_cap('delete_invitation');
            $admin_role->add_cap('delete_invitations');
            $admin_role->add_cap('send_invitations');
            
            // Onboarding capabilities
            $admin_role->add_cap('edit_onboarding');
            $admin_role->add_cap('edit_onboardings');
            $admin_role->add_cap('read_onboarding');
            $admin_role->add_cap('read_onboardings');
            $admin_role->add_cap('delete_onboarding');
            $admin_role->add_cap('delete_onboardings');
            
            // User management capabilities
            $admin_role->add_cap('manage_bureau_team');
            $admin_role->add_cap('manage_client_team');
        }
    }

    /**
     * Log activation for debugging
     *
     * @since    1.0.0
     */
    private static function log_activation() {
        $log_data = array(
            'version' => BUSINESS_HIERARCHY_MANAGER_VERSION,
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
            'tables_created' => get_option('business_hierarchy_manager_tables')
        );
        
        update_option('business_hierarchy_manager_activation_log', $log_data);
    }
}
