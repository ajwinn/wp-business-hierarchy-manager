<?php

/**
 * Plugin Name: Business Hierarchy Manager
 * Plugin URI: https://yourwebsite.com/business-hierarchy-manager
 * Description: A comprehensive WordPress plugin for managing business hierarchies with bureau companies, client companies, team members, and permissions.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: business-hierarchy-manager
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.5
 * Requires PHP: 7.4
 * Network: false
 *
 * @package BusinessHierarchyManager
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die('Direct access not allowed.');
}

/**
 * Plugin Version
 */
define('BUSINESS_HIERARCHY_MANAGER_VERSION', '1.0.0');

/**
 * Plugin Root File
 */
define('BUSINESS_HIERARCHY_MANAGER_FILE', __FILE__);

/**
 * Plugin Directory Path
 */
define('BUSINESS_HIERARCHY_MANAGER_PATH', plugin_dir_path(__FILE__));

/**
 * Plugin Directory URL
 */
define('BUSINESS_HIERARCHY_MANAGER_URL', plugin_dir_url(__FILE__));

/**
 * Plugin Basename
 */
define('BUSINESS_HIERARCHY_MANAGER_BASENAME', plugin_basename(__FILE__));

/**
 * Plugin Text Domain
 */
define('BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN', 'business-hierarchy-manager');

/**
 * Minimum WordPress Version Required
 */
define('BUSINESS_HIERARCHY_MANAGER_MIN_WP_VERSION', '5.0');

/**
 * Minimum PHP Version Required
 */
define('BUSINESS_HIERARCHY_MANAGER_MIN_PHP_VERSION', '7.4');

/**
 * Check system requirements and prevent activation if not met
 */
function business_hierarchy_manager_check_requirements() {
    $requirements_met = true;
    $messages = array();

    // Check WordPress version
    if (version_compare(get_bloginfo('version'), BUSINESS_HIERARCHY_MANAGER_MIN_WP_VERSION, '<')) {
        $requirements_met = false;
        $messages[] = sprintf(
            __('WordPress %s or higher is required. You are running version %s.', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN),
            BUSINESS_HIERARCHY_MANAGER_MIN_WP_VERSION,
            get_bloginfo('version')
        );
    }

    // Check PHP version
    if (version_compare(PHP_VERSION, BUSINESS_HIERARCHY_MANAGER_MIN_PHP_VERSION, '<')) {
        $requirements_met = false;
        $messages[] = sprintf(
            __('PHP %s or higher is required. You are running version %s.', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN),
            BUSINESS_HIERARCHY_MANAGER_MIN_PHP_VERSION,
            PHP_VERSION
        );
    }

    if (!$requirements_met) {
        deactivate_plugins(BUSINESS_HIERARCHY_MANAGER_BASENAME);
        
        $error_title = __('Plugin Activation Error', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN);
        $plugin_name = __('Business Hierarchy Manager', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN);
        $error_message = __('could not be activated due to the following requirements not being met:', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN);
        
        $output = '<h1>' . $error_title . '</h1>';
        $output .= '<p><strong>' . $plugin_name . '</strong> ' . $error_message . '</p>';
        $output .= '<ul><li>' . implode('</li><li>', $messages) . '</li></ul>';
        
        wp_die(
            $output,
            $error_title,
            array('back_link' => true)
        );
    }
}
/**
 * Load plugin textdomain for translations
 */
function business_hierarchy_manager_load_textdomain() {
    load_plugin_textdomain(
        BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN,
        false,
        dirname(BUSINESS_HIERARCHY_MANAGER_BASENAME) . '/languages/'
    );
}
add_action('plugins_loaded', 'business_hierarchy_manager_load_textdomain');

/**
 * The code that runs during plugin activation.
 */
function activate_business_hierarchy_manager() {
    // Check requirements before activation
    business_hierarchy_manager_check_requirements();
    
    // Set activation flag first
    add_option('business_hierarchy_manager_activated', true);
    add_option('business_hierarchy_manager_version', BUSINESS_HIERARCHY_MANAGER_VERSION);
    
    // Create database tables (minimal approach)
    business_hierarchy_manager_create_tables();
    
    // Create user roles (minimal approach)
    business_hierarchy_manager_create_roles();
    
    // Set admin capabilities (minimal approach)
    business_hierarchy_manager_set_admin_capabilities();
    
    // Log activation
    update_option('business_hierarchy_manager_activation_log', array(
        'version' => BUSINESS_HIERARCHY_MANAGER_VERSION,
        'timestamp' => current_time('mysql'),
        'user_id' => get_current_user_id()
    ));
}

/**
 * Minimal database table creation
 */
function business_hierarchy_manager_create_tables() {
    global $wpdb;
    
    // Check if tables already exist
    $existing_tables = get_option('business_hierarchy_manager_tables', array());
    if (!empty($existing_tables)) {
        return;
    }
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Only create essential tables for now
    $table_user_companies = $wpdb->prefix . 'business_hierarchy_user_companies';
    $sql_user_companies = "CREATE TABLE $table_user_companies (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        company_id bigint(20) NOT NULL,
        company_type varchar(20) NOT NULL,
        role varchar(50) NOT NULL,
        is_primary tinyint(1) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_company (user_id, company_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_user_companies);
    
    update_option('business_hierarchy_manager_tables', array(
        'user_companies' => $table_user_companies
    ));
}

/**
 * Minimal user role creation
 */
function business_hierarchy_manager_create_roles() {
    $existing_roles = wp_roles()->get_names();
    
    if (!array_key_exists('bureau_primary', $existing_roles)) {
        add_role('bureau_primary', 'Bureau Primary Member', array(
            'read' => true,
            'edit_bureau_company' => true,
            'edit_bureau_companies' => true,
            'edit_client_companies' => true,
            'send_invitations' => true
        ));
    }
    
    if (!array_key_exists('client_primary', $existing_roles)) {
        add_role('client_primary', 'Client Primary Member', array(
            'read' => true,
            'edit_client_company' => true,
            'send_invitations' => true
        ));
    }
}

/**
 * Minimal admin capability assignment
 */
function business_hierarchy_manager_set_admin_capabilities() {
    $admin_role = get_role('administrator');
    if ($admin_role) {
        $caps = array(
            'edit_bureau_company', 'edit_bureau_companies',
            'edit_client_company', 'edit_client_companies',
            'send_invitations'
        );
        
        foreach ($caps as $cap) {
            if (!$admin_role->has_cap($cap)) {
                $admin_role->add_cap($cap);
            }
        }
    }
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_business_hierarchy_manager() {
    require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'includes/class-deactivator.php';
    Business_Hierarchy_Manager_Deactivator::deactivate();
}

/**
 * Register activation and deactivation hooks
 */
register_activation_hook(__FILE__, 'activate_business_hierarchy_manager');
register_deactivation_hook(__FILE__, 'deactivate_business_hierarchy_manager');

/**
 * Autoloader for plugin classes
 */
function business_hierarchy_manager_autoloader($class_name) {
    // Only autoload our plugin classes
    if (strpos($class_name, 'Business_Hierarchy_Manager') === false) {
        return;
    }

    // Convert class name to file name
    $class_file = str_replace('_', '-', strtolower($class_name));
    $class_file = 'class-' . $class_file . '.php';

    // Define possible paths
    $paths = array(
        BUSINESS_HIERARCHY_MANAGER_PATH . 'includes/',
        BUSINESS_HIERARCHY_MANAGER_PATH . 'admin/',
        BUSINESS_HIERARCHY_MANAGER_PATH . 'public/',
        BUSINESS_HIERARCHY_MANAGER_PATH . 'core/',
        BUSINESS_HIERARCHY_MANAGER_PATH . 'database/',
    );

    // Try to find and include the class file
    foreach ($paths as $path) {
        $file_path = $path . $class_file;
        if (file_exists($file_path)) {
            require_once $file_path;
            return;
        }
    }
}
spl_autoload_register('business_hierarchy_manager_autoloader');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'includes/class-business-hierarchy-manager.php';

/**
 * Initialize the plugin
 */
// Temporarily disabled to prevent memory issues during activation
// function run_business_hierarchy_manager() {
//     $plugin = new Business_Hierarchy_Manager();
//     $plugin->run();
// }

/**
 * Begin execution of the plugin.
 * 
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
// Temporarily disabled to prevent memory issues during activation
// add_action('plugins_loaded', 'run_business_hierarchy_manager');

/**
 * Add action links to plugin page
 */
function business_hierarchy_manager_add_action_links($links) {
    $settings_link = array(
        '<a href="' . admin_url('admin.php?page=business-hierarchy-manager') . '">' . __('Settings', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . '</a>',
    );
    return array_merge($settings_link, $links);
}
add_filter('plugin_action_links_' . BUSINESS_HIERARCHY_MANAGER_BASENAME, 'business_hierarchy_manager_add_action_links');

/**
 * Add plugin meta links
 */
function business_hierarchy_manager_add_meta_links($links, $file) {
    if ($file == BUSINESS_HIERARCHY_MANAGER_BASENAME) {
        $links[] = '<a href="https://yourwebsite.com/docs/business-hierarchy-manager" target="_blank">' . __('Documentation', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . '</a>';
        $links[] = '<a href="https://yourwebsite.com/support" target="_blank">' . __('Support', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . '</a>';
    }
    return $links;
}
add_filter('plugin_row_meta', 'business_hierarchy_manager_add_meta_links', 10, 2);

/**
 * Display admin notice if requirements are not met
 */
function business_hierarchy_manager_admin_notice_requirements() {
    if (version_compare(get_bloginfo('version'), BUSINESS_HIERARCHY_MANAGER_MIN_WP_VERSION, '<') ||
        version_compare(PHP_VERSION, BUSINESS_HIERARCHY_MANAGER_MIN_PHP_VERSION, '<')) {
        
        echo '<div>';
        echo '<p><strong>' . __('Business Hierarchy Manager', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . '</strong> ';
        echo __('requires WordPress ', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . BUSINESS_HIERARCHY_MANAGER_MIN_WP_VERSION;
        echo __(' and PHP ', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . BUSINESS_HIERARCHY_MANAGER_MIN_PHP_VERSION;
        echo __(' or higher to function properly.', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'business_hierarchy_manager_admin_notice_requirements');

/**
 * Add custom database tables to WordPress database repair/optimize functionality
 */
function business_hierarchy_manager_repair_tables($tables) {
    global $wpdb;
    
    // Add any custom tables here if you create them
    // $tables[] = $wpdb->prefix . 'business_hierarchy_custom_table';
    
    return $tables;
}
add_filter('tables_to_repair', 'business_hierarchy_manager_repair_tables');

/**
 * Log plugin errors if WP_DEBUG is enabled
 */
function business_hierarchy_manager_log_error($message, $context = array()) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log(
            sprintf(
                '[Business Hierarchy Manager] %s | Context: %s',
                $message,
                !empty($context) ? wp_json_encode($context) : 'none'
            )
        );
    }
}

/**
 * Get plugin information
 */
function business_hierarchy_manager_get_plugin_info() {
    if (!function_exists('get_plugin_data')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    return get_plugin_data(BUSINESS_HIERARCHY_MANAGER_FILE);
}

/**
 * Check if plugin dependencies are active
 */
function business_hierarchy_manager_check_dependencies() {
    $dependencies = array();
    
    // Add any required plugins here
    // Example:
    // if (!is_plugin_active('required-plugin/required-plugin.php')) {
    //     $dependencies[] = 'Required Plugin Name';
    // }
    
    if (!empty($dependencies)) {
        add_action('admin_notices', function() use ($dependencies) {
            echo '<div>';
            echo '<p><strong>' . __('Business Hierarchy Manager', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN) . '</strong> ';
            echo __('requires the following plugins to be active: ', BUSINESS_HIERARCHY_MANAGER_TEXT_DOMAIN);
            echo '<strong>' . implode(', ', $dependencies) . '</strong></p>';
            echo '</div>';
        });
    }
}
add_action('admin_init', 'business_hierarchy_manager_check_dependencies');

/**
 * Add basic admin menu
 */
function business_hierarchy_manager_admin_menu() {
    add_menu_page(
        'Business Hierarchy Manager',
        'Business Hierarchy',
        'manage_options',
        'business-hierarchy-manager',
        'business_hierarchy_manager_admin_page',
        'dashicons-groups',
        30
    );
}
add_action('admin_menu', 'business_hierarchy_manager_admin_menu');

/**
 * Basic admin page
 */
function business_hierarchy_manager_admin_page() {
    echo '<div class="wrap">';
    echo '<h1>Business Hierarchy Manager</h1>';
    echo '<p>Plugin is activated successfully!</p>';
    echo '<p>Version: ' . BUSINESS_HIERARCHY_MANAGER_VERSION . '</p>';
    echo '<p>Database tables created: ' . (get_option('business_hierarchy_manager_tables') ? 'Yes' : 'No') . '</p>';
    echo '<p>User roles created: ' . (get_role('bureau_primary') ? 'Yes' : 'No') . '</p>';
    echo '</div>';
}

// That's all, folks! The rest happens in the class files.