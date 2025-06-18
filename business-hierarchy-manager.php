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
            // Basic capabilities
            'edit_bureau_company', 'edit_bureau_companies',
            'edit_client_company', 'edit_client_companies',
            'edit_invitation', 'edit_invitations',
            'send_invitations',
            
            // Read capabilities
            'read_bureau_company', 'read_bureau_companies',
            'read_client_company', 'read_client_companies',
            'read_invitation', 'read_invitations',
            
            // Delete capabilities
            'delete_bureau_company', 'delete_bureau_companies',
            'delete_client_company', 'delete_client_companies',
            'delete_invitation', 'delete_invitations',
            
            // Publish capabilities
            'publish_bureau_companies',
            'publish_client_companies',
            'publish_invitations',
            
            // Private post capabilities
            'read_private_bureau_companies',
            'read_private_client_companies',
            'read_private_invitations',
            'edit_private_bureau_companies',
            'edit_private_client_companies',
            'edit_private_invitations',
            'delete_private_bureau_companies',
            'delete_private_client_companies',
            'delete_private_invitations',
            
            // Published post capabilities
            'edit_published_bureau_companies',
            'edit_published_client_companies',
            'edit_published_invitations',
            'delete_published_bureau_companies',
            'delete_published_client_companies',
            'delete_published_invitations',
            
            // Others posts capabilities
            'edit_others_bureau_companies',
            'edit_others_client_companies',
            'edit_others_invitations',
            'delete_others_bureau_companies',
            'delete_others_client_companies',
            'delete_others_invitations',
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

// Include template functions
require_once plugin_dir_path(__FILE__) . 'includes/template-functions.php';

// Include core classes
require_once plugin_dir_path(__FILE__) . 'includes/class-business-hierarchy-manager.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-loader.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-i18n.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-activator.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-deactivator.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

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
    // Main menu
    add_menu_page(
        'Business Hierarchy Manager',
        'Business Hierarchy',
        'manage_options',
        'business-hierarchy-manager',
        'business_hierarchy_manager_admin_page',
        'dashicons-groups',
        30
    );
    
    // Dashboard submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'business-hierarchy-manager',
        'business_hierarchy_manager_admin_page'
    );
    
    // Bureau Companies submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Bureau Companies',
        'Bureau Companies',
        'edit_bureau_companies',
        'edit.php?post_type=bureau_company'
    );
    
    // Add New Bureau submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Add New Bureau',
        'Add New Bureau',
        'edit_bureau_companies',
        'business-hierarchy-add-bureau',
        'business_hierarchy_manager_add_bureau_page'
    );
    
    // Client Companies submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Client Companies',
        'Client Companies',
        'edit_client_companies',
        'edit.php?post_type=client_company'
    );
    
    // Add New Client submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Add New Client',
        'Add New Client',
        'edit_client_companies',
        'post-new.php?post_type=client_company'
    );
    
    // Invitations submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Invitations',
        'Invitations',
        'edit_invitations',
        'edit.php?post_type=invitation'
    );
    
    // Add New Invitation submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Add New Invitation',
        'Add New Invitation',
        'edit_invitations',
        'post-new.php?post_type=invitation'
    );
    
    // Settings submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Settings',
        'Settings',
        'manage_options',
        'business-hierarchy-settings',
        'business_hierarchy_manager_settings_page'
    );
    
    // Test submenu
    add_submenu_page(
        'business-hierarchy-manager',
        'Test Page',
        'Test',
        'manage_options',
        'business-hierarchy-test',
        'business_hierarchy_manager_test_page'
    );
}
add_action('admin_menu', 'business_hierarchy_manager_admin_menu');

/**
 * Basic admin page
 */
function business_hierarchy_manager_admin_page() {
    // Show success message if bureau was created
    if (isset($_GET['bureau_created']) && $_GET['bureau_created'] == '1') {
        echo '<div class="notice notice-success is-dismissible"><p>Bureau company created successfully!</p></div>';
    }
    
    // Load admin page template
    business_hierarchy_manager_load_admin_template('dashboard.php', array(
        'version' => BUSINESS_HIERARCHY_MANAGER_VERSION,
        'tables_created' => get_option('business_hierarchy_manager_tables') ? 'Yes' : 'No',
        'roles_created' => get_role('bureau_primary') ? 'Yes' : 'No',
    ));
}

/**
 * Settings page
 */
function business_hierarchy_manager_settings_page() {
    business_hierarchy_manager_load_admin_template('settings.php');
}

/**
 * Test page to verify partial template structure
 */
function business_hierarchy_manager_test_page() {
    // Load the test template
    business_hierarchy_manager_load_admin_template('test-page.php');
}

/**
 * Add New Bureau page
 */
function business_hierarchy_manager_add_bureau_page() {
    // Enqueue form JavaScript
    wp_enqueue_script(
        'bureau-form-js',
        plugin_dir_url(__FILE__) . 'templates/admin/assets/bureau-form.js',
        array('jquery'),
        BUSINESS_HIERARCHY_MANAGER_VERSION,
        true
    );
    // Load the bureau form template
    business_hierarchy_manager_load_admin_template('bureau-form.php');
}

/**
 * Register custom post types
 */
function business_hierarchy_manager_register_post_types() {
    // Bureau Company post type
    register_post_type('bureau_company', array(
        'labels' => array(
            'name' => 'Bureau Companies',
            'singular_name' => 'Bureau Company',
            'menu_name' => 'Bureau Companies',
            'add_new' => 'Add New Bureau',
            'add_new_item' => 'Add New Bureau Company',
            'edit_item' => 'Edit Bureau Company',
            'new_item' => 'New Bureau Company',
            'view_item' => 'View Bureau Company',
            'search_items' => 'Search Bureau Companies',
            'not_found' => 'No bureau companies found',
            'not_found_in_trash' => 'No bureau companies found in trash',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // We'll add it to our custom menu
        'capability_type' => array('bureau_company', 'bureau_companies'),
        'map_meta_cap' => true,
        'capabilities' => array(
            'edit_post' => 'edit_bureau_company',
            'read_post' => 'read_bureau_company',
            'delete_post' => 'delete_bureau_company',
            'edit_posts' => 'edit_bureau_companies',
            'edit_others_posts' => 'edit_bureau_companies',
            'publish_posts' => 'edit_bureau_companies',
            'read_private_posts' => 'read_bureau_companies',
            'delete_posts' => 'delete_bureau_companies',
            'delete_private_posts' => 'delete_bureau_companies',
            'delete_published_posts' => 'delete_bureau_companies',
            'delete_others_posts' => 'delete_bureau_companies',
            'edit_private_posts' => 'edit_bureau_companies',
            'edit_published_posts' => 'edit_bureau_companies',
        ),
        'supports' => array('title', 'editor', 'custom-fields'),
        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,
    ));

    // Client Company post type
    register_post_type('client_company', array(
        'labels' => array(
            'name' => 'Client Companies',
            'singular_name' => 'Client Company',
            'menu_name' => 'Client Companies',
            'add_new' => 'Add New Client',
            'add_new_item' => 'Add New Client Company',
            'edit_item' => 'Edit Client Company',
            'new_item' => 'New Client Company',
            'view_item' => 'View Client Company',
            'search_items' => 'Search Client Companies',
            'not_found' => 'No client companies found',
            'not_found_in_trash' => 'No client companies found in trash',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // We'll add it to our custom menu
        'capability_type' => array('client_company', 'client_companies'),
        'map_meta_cap' => true,
        'capabilities' => array(
            'edit_post' => 'edit_client_company',
            'read_post' => 'read_client_company',
            'delete_post' => 'delete_client_company',
            'edit_posts' => 'edit_client_companies',
            'edit_others_posts' => 'edit_client_companies',
            'publish_posts' => 'edit_client_companies',
            'read_private_posts' => 'read_client_companies',
            'delete_posts' => 'delete_client_companies',
            'delete_private_posts' => 'delete_client_companies',
            'delete_published_posts' => 'delete_client_companies',
            'delete_others_posts' => 'delete_client_companies',
            'edit_private_posts' => 'edit_client_companies',
            'edit_published_posts' => 'edit_client_companies',
        ),
        'supports' => array('title', 'editor', 'custom-fields'),
        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,
    ));

    // Invitation post type
    register_post_type('invitation', array(
        'labels' => array(
            'name' => 'Invitations',
            'singular_name' => 'Invitation',
            'menu_name' => 'Invitations',
            'add_new' => 'Add New Invitation',
            'add_new_item' => 'Add New Invitation',
            'edit_item' => 'Edit Invitation',
            'new_item' => 'New Invitation',
            'view_item' => 'View Invitation',
            'search_items' => 'Search Invitations',
            'not_found' => 'No invitations found',
            'not_found_in_trash' => 'No invitations found in trash',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // We'll add it to our custom menu
        'capability_type' => array('invitation', 'invitations'),
        'map_meta_cap' => true,
        'capabilities' => array(
            'edit_post' => 'edit_invitation',
            'read_post' => 'read_invitation',
            'delete_post' => 'delete_invitation',
            'edit_posts' => 'edit_invitations',
            'edit_others_posts' => 'edit_invitations',
            'publish_posts' => 'edit_invitations',
            'read_private_posts' => 'read_invitations',
            'delete_posts' => 'delete_invitations',
            'delete_private_posts' => 'delete_invitations',
            'delete_published_posts' => 'delete_invitations',
            'delete_others_posts' => 'delete_invitations',
            'edit_private_posts' => 'edit_invitations',
            'edit_published_posts' => 'edit_invitations',
        ),
        'supports' => array('title', 'custom-fields'),
        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,
    ));
}
add_action('init', 'business_hierarchy_manager_register_post_types');

/**
 * Map meta capabilities for custom post types
 */
function business_hierarchy_manager_map_meta_caps($caps, $cap, $user_id, $args) {
    // Bureau Company capabilities
    if (in_array($cap, array('edit_bureau_company', 'read_bureau_company', 'delete_bureau_company'))) {
        $post_id = isset($args[0]) ? $args[0] : 0;
        $post = get_post($post_id);
        
        if ($post && $post->post_type === 'bureau_company') {
            $caps = array();
            
            if ($cap === 'edit_bureau_company') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'edit_bureau_companies';
                } else {
                    $caps[] = 'edit_others_bureau_companies';
                }
            } elseif ($cap === 'read_bureau_company') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'read_bureau_companies';
                } else {
                    $caps[] = 'read_others_bureau_companies';
                }
            } elseif ($cap === 'delete_bureau_company') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'delete_bureau_companies';
                } else {
                    $caps[] = 'delete_others_bureau_companies';
                }
            }
        }
    }
    
    // Client Company capabilities
    if (in_array($cap, array('edit_client_company', 'read_client_company', 'delete_client_company'))) {
        $post_id = isset($args[0]) ? $args[0] : 0;
        $post = get_post($post_id);
        
        if ($post && $post->post_type === 'client_company') {
            $caps = array();
            
            if ($cap === 'edit_client_company') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'edit_client_companies';
                } else {
                    $caps[] = 'edit_others_client_companies';
                }
            } elseif ($cap === 'read_client_company') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'read_client_companies';
                } else {
                    $caps[] = 'read_others_client_companies';
                }
            } elseif ($cap === 'delete_client_company') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'delete_client_companies';
                } else {
                    $caps[] = 'delete_others_client_companies';
                }
            }
        }
    }
    
    // Invitation capabilities
    if (in_array($cap, array('edit_invitation', 'read_invitation', 'delete_invitation'))) {
        $post_id = isset($args[0]) ? $args[0] : 0;
        $post = get_post($post_id);
        
        if ($post && $post->post_type === 'invitation') {
            $caps = array();
            
            if ($cap === 'edit_invitation') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'edit_invitations';
                } else {
                    $caps[] = 'edit_others_invitations';
                }
            } elseif ($cap === 'read_invitation') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'read_invitations';
                } else {
                    $caps[] = 'read_others_invitations';
                }
            } elseif ($cap === 'delete_invitation') {
                if ($user_id == $post->post_author) {
                    $caps[] = 'delete_invitations';
                } else {
                    $caps[] = 'delete_others_invitations';
                }
            }
        }
    }
    
    return $caps;
}
add_filter('map_meta_cap', 'business_hierarchy_manager_map_meta_caps', 10, 4);

/**
 * Custom admin page handling for bureau companies
 */
function business_hierarchy_manager_admin_init() {
    // Only run on admin pages
    if (!is_admin()) {
        return;
    }
    
    // Check if we're on a bureau company edit page
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        // Remove default meta boxes
        add_action('add_meta_boxes', 'business_hierarchy_manager_remove_default_meta_boxes', 999);
        
        // Add custom meta boxes
        add_action('add_meta_boxes', 'business_hierarchy_manager_add_bureau_meta_boxes');
        
        // Save custom fields
        add_action('save_post', 'business_hierarchy_manager_save_bureau_fields', 10, 2);
        
        // Customize the title placeholder
        add_filter('enter_title_here', 'business_hierarchy_manager_bureau_title_placeholder');
        
        // Hide the content editor
        add_action('admin_head', 'business_hierarchy_manager_hide_content_editor');
    }
}
add_action('admin_init', 'business_hierarchy_manager_admin_init');

/**
 * Customize bureau company admin pages
 */
function business_hierarchy_manager_admin_scripts() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        // Remove default meta boxes
        add_action('add_meta_boxes', 'business_hierarchy_manager_remove_default_meta_boxes', 999);
        
        // Add custom meta boxes
        add_action('add_meta_boxes', 'business_hierarchy_manager_add_bureau_meta_boxes');
        
        // Save custom fields
        add_action('save_post', 'business_hierarchy_manager_save_bureau_fields', 10, 2);
        
        // Customize the title placeholder
        add_filter('enter_title_here', 'business_hierarchy_manager_bureau_title_placeholder');
        
        // Hide the content editor
        add_action('admin_head', 'business_hierarchy_manager_hide_content_editor');
    }
}
add_action('admin_enqueue_scripts', 'business_hierarchy_manager_admin_scripts');

/**
 * Customize bureau company admin pages - direct approach
 */
function business_hierarchy_manager_remove_default_meta_boxes() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        remove_meta_box('postexcerpt', 'bureau_company', 'normal');
        remove_meta_box('trackbacksdiv', 'bureau_company', 'normal');
        remove_meta_box('commentstatusdiv', 'bureau_company', 'normal');
        remove_meta_box('commentsdiv', 'bureau_company', 'normal');
        remove_meta_box('slugdiv', 'bureau_company', 'normal');
        remove_meta_box('authordiv', 'bureau_company', 'normal');
        remove_meta_box('postimagediv', 'bureau_company', 'normal');
        remove_meta_box('postcustom', 'bureau_company', 'normal'); // Custom Fields
    }
}
add_action('add_meta_boxes', 'business_hierarchy_manager_remove_default_meta_boxes', 999);

function business_hierarchy_manager_add_bureau_meta_boxes() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        add_meta_box(
            'bureau_details',
            'Bureau Details',
            'business_hierarchy_manager_bureau_details_callback',
            'bureau_company',
            'normal',
            'high'
        );
        
        add_meta_box(
            'primary_user_details',
            'Primary User Details',
            'business_hierarchy_manager_primary_user_callback',
            'bureau_company',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'business_hierarchy_manager_add_bureau_meta_boxes');

function business_hierarchy_manager_bureau_title_placeholder($title) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        return 'Enter Bureau Company Name';
    }
    return $title;
}
add_filter('enter_title_here', 'business_hierarchy_manager_bureau_title_placeholder');

function business_hierarchy_manager_hide_content_editor() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        echo '<style>';
        echo '#postdivrich { display: none; }';
        echo '.bureau-form .form-table th { width: 150px; }';
        echo '.bureau-form .form-table td { padding: 15px 10px; }';
        echo '.bureau-form .form-table input[type="text"], .bureau-form .form-table input[type="email"], .bureau-form .form-table input[type="tel"] { width: 100%; max-width: 400px; }';
        echo '.bureau-form .description { color: #666; font-style: italic; margin-top: 5px; }';
        echo '.editor-post-visibility { display: none !important; }';
        echo '.editor-post-visibility__dialog { display: none !important; }';
        echo '</style>';
    }
}
add_action('admin_head', 'business_hierarchy_manager_hide_content_editor');

/**
 * Save bureau company custom fields
 */
function business_hierarchy_manager_save_bureau_fields($post_id, $post) {
    // Security checks
    if (!isset($_POST['business_hierarchy_manager_bureau_nonce']) || 
        !wp_verify_nonce($_POST['business_hierarchy_manager_bureau_nonce'], 'business_hierarchy_manager_save_bureau')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if ($post->post_type !== 'bureau_company') {
        return;
    }
    
    // Save bureau details
    if (isset($_POST['bureau_street1'])) {
        update_post_meta($post_id, '_bureau_street1', sanitize_text_field($_POST['bureau_street1']));
    }
    
    if (isset($_POST['bureau_street2'])) {
        update_post_meta($post_id, '_bureau_street2', sanitize_text_field($_POST['bureau_street2']));
    }
    
    if (isset($_POST['bureau_city'])) {
        update_post_meta($post_id, '_bureau_city', sanitize_text_field($_POST['bureau_city']));
    }
    
    if (isset($_POST['bureau_state'])) {
        update_post_meta($post_id, '_bureau_state', sanitize_text_field($_POST['bureau_state']));
    }
    
    if (isset($_POST['bureau_zip'])) {
        update_post_meta($post_id, '_bureau_zip', sanitize_text_field($_POST['bureau_zip']));
    }
    
    if (isset($_POST['bureau_phone'])) {
        update_post_meta($post_id, '_bureau_phone', sanitize_text_field($_POST['bureau_phone']));
    }
    
    // Save primary user details
    if (isset($_POST['primary_first_name'])) {
        update_post_meta($post_id, '_primary_first_name', sanitize_text_field($_POST['primary_first_name']));
    }
    
    if (isset($_POST['primary_last_name'])) {
        update_post_meta($post_id, '_primary_last_name', sanitize_text_field($_POST['primary_last_name']));
    }
    
    if (isset($_POST['primary_email'])) {
        update_post_meta($post_id, '_primary_email', sanitize_email($_POST['primary_email']));
    }
    
    // Set post status to published
    if ($post->post_status === 'auto-draft') {
        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'publish'
        ));
        
        // Redirect to the bureau companies list
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Bureau company created successfully!</p></div>';
        });
        
        // Only redirect if this is a new post
        if (!wp_doing_ajax()) {
            wp_redirect(admin_url('edit.php?post_type=bureau_company&created=1'));
            exit;
        }
    }
}

/**
 * Bureau details meta box callback
 */
function business_hierarchy_manager_bureau_details_callback($post) {
    wp_nonce_field('business_hierarchy_manager_save_bureau', 'business_hierarchy_manager_bureau_nonce');
    
    // Get data for template
    $data = array(
        'street1' => get_post_meta($post->ID, '_bureau_street1', true),
        'street2' => get_post_meta($post->ID, '_bureau_street2', true),
        'city' => get_post_meta($post->ID, '_bureau_city', true),
        'state' => get_post_meta($post->ID, '_bureau_state', true),
        'zip' => get_post_meta($post->ID, '_bureau_zip', true),
        'phone' => get_post_meta($post->ID, '_bureau_phone', true),
    );
    
    // Load template with data
    business_hierarchy_manager_load_meta_box('bureau-details.php', $data);
}

/**
 * Primary user details meta box callback
 */
function business_hierarchy_manager_primary_user_callback($post) {
    // Get data for template
    $data = array(
        'primary_first_name' => get_post_meta($post->ID, '_primary_first_name', true),
        'primary_last_name' => get_post_meta($post->ID, '_primary_last_name', true),
        'primary_email' => get_post_meta($post->ID, '_primary_email', true),
    );
    
    // Load template with data
    business_hierarchy_manager_load_meta_box('primary-user.php', $data);
}

/**
 * Customize the publish section for bureau companies
 */
function business_hierarchy_manager_customize_publish_section() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'bureau_company') {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Change "Publish" button text to "Create Bureau"
            $('.editor-post-publish-button').text('Create Bureau');
            $('.editor-post-publish-panel__toggle').text('Create Bureau');
            
            // Change "Update" button text to "Update Bureau"
            $('.editor-post-publish-button').each(function() {
                if ($(this).text() === 'Update') {
                    $(this).text('Update Bureau');
                }
            });
            
            // Change "Publish" panel title
            $('.editor-post-publish-panel__header-publish-button').text('Create Bureau');
            
            // Hide the "Visibility" section since it's not relevant for bureaus
            $('.editor-post-visibility').hide();
            
            // Change "Status" to "Bureau Status"
            $('.editor-post-status').find('label').text('Bureau Status:');
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'business_hierarchy_manager_customize_publish_section');

// That's all, folks! The rest happens in the class files.

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('bhm-admin-tailwind', plugin_dir_url(__FILE__) . 'assets/css/admin.css', [], '1.0');
});