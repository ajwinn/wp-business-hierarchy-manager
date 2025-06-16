<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/admin
 */
class Business_Hierarchy_Manager_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            BUSINESS_HIERARCHY_MANAGER_URL . 'admin/css/admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            BUSINESS_HIERARCHY_MANAGER_URL . 'admin/js/admin.js',
            array('jquery'),
            $this->version,
            false
        );
    }

    /**
     * Add plugin admin menu
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            __('Business Hierarchy Manager', 'business-hierarchy-manager'),
            __('Business Hierarchy', 'business-hierarchy-manager'),
            'manage_options',
            'business-hierarchy-manager',
            array($this, 'display_plugin_admin_page'),
            'dashicons-groups',
            30
        );

        add_submenu_page(
            'business-hierarchy-manager',
            __('Dashboard', 'business-hierarchy-manager'),
            __('Dashboard', 'business-hierarchy-manager'),
            'manage_options',
            'business-hierarchy-manager',
            array($this, 'display_plugin_admin_page')
        );

        add_submenu_page(
            'business-hierarchy-manager',
            __('Bureau Companies', 'business-hierarchy-manager'),
            __('Bureau Companies', 'business-hierarchy-manager'),
            'edit_bureau_companies',
            'edit.php?post_type=bureau_company'
        );

        add_submenu_page(
            'business-hierarchy-manager',
            __('Client Companies', 'business-hierarchy-manager'),
            __('Client Companies', 'business-hierarchy-manager'),
            'edit_client_companies',
            'edit.php?post_type=client_company'
        );

        add_submenu_page(
            'business-hierarchy-manager',
            __('Invitations', 'business-hierarchy-manager'),
            __('Invitations', 'business-hierarchy-manager'),
            'edit_invitations',
            'edit.php?post_type=invitation'
        );

        add_submenu_page(
            'business-hierarchy-manager',
            __('Onboardings', 'business-hierarchy-manager'),
            __('Onboardings', 'business-hierarchy-manager'),
            'edit_onboardings',
            'edit.php?post_type=onboarding'
        );

        add_submenu_page(
            'business-hierarchy-manager',
            __('Settings', 'business-hierarchy-manager'),
            __('Settings', 'business-hierarchy-manager'),
            'manage_options',
            'business-hierarchy-manager-settings',
            array($this, 'display_plugin_settings_page')
        );
    }

    /**
     * Display the admin page
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once BUSINESS_HIERARCHY_MANAGER_PATH . 'admin/partials/admin-display.php';
    }

    /**
     * Display the settings page
     *
     * @since    1.0.0
     */
    public function display_plugin_settings_page() {
        include_once BUSINESS_HIERARCHY_MANAGER_PATH . 'admin/partials/admin-settings.php';
    }

    /**
     * Register custom post types
     *
     * @since    1.0.0
     */
    public function register_post_types() {
        // Bureau Company post type
        register_post_type('bureau_company', array(
            'labels' => array(
                'name' => __('Bureau Companies', 'business-hierarchy-manager'),
                'singular_name' => __('Bureau Company', 'business-hierarchy-manager'),
                'menu_name' => __('Bureau Companies', 'business-hierarchy-manager'),
                'add_new' => __('Add New Bureau', 'business-hierarchy-manager'),
                'add_new_item' => __('Add New Bureau Company', 'business-hierarchy-manager'),
                'edit_item' => __('Edit Bureau Company', 'business-hierarchy-manager'),
                'new_item' => __('New Bureau Company', 'business-hierarchy-manager'),
                'view_item' => __('View Bureau Company', 'business-hierarchy-manager'),
                'search_items' => __('Search Bureau Companies', 'business-hierarchy-manager'),
                'not_found' => __('No bureau companies found', 'business-hierarchy-manager'),
                'not_found_in_trash' => __('No bureau companies found in trash', 'business-hierarchy-manager'),
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
                'name' => __('Client Companies', 'business-hierarchy-manager'),
                'singular_name' => __('Client Company', 'business-hierarchy-manager'),
                'menu_name' => __('Client Companies', 'business-hierarchy-manager'),
                'add_new' => __('Add New Client', 'business-hierarchy-manager'),
                'add_new_item' => __('Add New Client Company', 'business-hierarchy-manager'),
                'edit_item' => __('Edit Client Company', 'business-hierarchy-manager'),
                'new_item' => __('New Client Company', 'business-hierarchy-manager'),
                'view_item' => __('View Client Company', 'business-hierarchy-manager'),
                'search_items' => __('Search Client Companies', 'business-hierarchy-manager'),
                'not_found' => __('No client companies found', 'business-hierarchy-manager'),
                'not_found_in_trash' => __('No client companies found in trash', 'business-hierarchy-manager'),
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
                'name' => __('Invitations', 'business-hierarchy-manager'),
                'singular_name' => __('Invitation', 'business-hierarchy-manager'),
                'menu_name' => __('Invitations', 'business-hierarchy-manager'),
                'add_new' => __('Add New Invitation', 'business-hierarchy-manager'),
                'add_new_item' => __('Add New Invitation', 'business-hierarchy-manager'),
                'edit_item' => __('Edit Invitation', 'business-hierarchy-manager'),
                'new_item' => __('New Invitation', 'business-hierarchy-manager'),
                'view_item' => __('View Invitation', 'business-hierarchy-manager'),
                'search_items' => __('Search Invitations', 'business-hierarchy-manager'),
                'not_found' => __('No invitations found', 'business-hierarchy-manager'),
                'not_found_in_trash' => __('No invitations found in trash', 'business-hierarchy-manager'),
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

        // Onboarding post type
        register_post_type('onboarding', array(
            'labels' => array(
                'name' => __('Onboardings', 'business-hierarchy-manager'),
                'singular_name' => __('Onboarding', 'business-hierarchy-manager'),
                'menu_name' => __('Onboardings', 'business-hierarchy-manager'),
                'add_new' => __('Add New Onboarding', 'business-hierarchy-manager'),
                'add_new_item' => __('Add New Onboarding', 'business-hierarchy-manager'),
                'edit_item' => __('Edit Onboarding', 'business-hierarchy-manager'),
                'new_item' => __('New Onboarding', 'business-hierarchy-manager'),
                'view_item' => __('View Onboarding', 'business-hierarchy-manager'),
                'search_items' => __('Search Onboardings', 'business-hierarchy-manager'),
                'not_found' => __('No onboardings found', 'business-hierarchy-manager'),
                'not_found_in_trash' => __('No onboardings found in trash', 'business-hierarchy-manager'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false, // We'll add it to our custom menu
            'capability_type' => array('onboarding', 'onboardings'),
            'map_meta_cap' => true,
            'capabilities' => array(
                'edit_post' => 'edit_onboarding',
                'read_post' => 'read_onboarding',
                'delete_post' => 'delete_onboarding',
                'edit_posts' => 'edit_onboardings',
                'edit_others_posts' => 'edit_onboardings',
                'publish_posts' => 'edit_onboardings',
                'read_private_posts' => 'read_onboardings',
                'delete_posts' => 'delete_onboardings',
                'delete_private_posts' => 'delete_onboardings',
                'delete_published_posts' => 'delete_onboardings',
                'delete_others_posts' => 'delete_onboardings',
                'edit_private_posts' => 'edit_onboardings',
                'edit_published_posts' => 'edit_onboardings',
            ),
            'supports' => array('title', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
            'query_var' => false,
        ));
    }

    /**
     * Register user roles
     *
     * @since    1.0.0
     */
    public function register_user_roles() {
        // This is handled in the activator class
        // Roles are created during plugin activation
    }

    /**
     * Register capabilities
     *
     * @since    1.0.0
     */
    public function register_capabilities() {
        // This is handled in the activator class
        // Capabilities are set during plugin activation
    }

    /**
     * Add meta boxes
     *
     * @since    1.0.0
     */
    public function add_meta_boxes() {
        add_meta_box(
            'company_meta_box',
            __('Company Information', 'business-hierarchy-manager'),
            array($this, 'company_meta_box_callback'),
            'bureau_company',
            'normal',
            'high'
        );

        add_meta_box(
            'company_meta_box',
            __('Company Information', 'business-hierarchy-manager'),
            array($this, 'company_meta_box_callback'),
            'client_company',
            'normal',
            'high'
        );
    }

    /**
     * Meta box callback
     *
     * @since    1.0.0
     * @param    WP_Post    $post    The post object
     */
    public function company_meta_box_callback($post) {
        include BUSINESS_HIERARCHY_MANAGER_PATH . 'admin/partials/company-meta-box.php';
    }

    /**
     * Save meta boxes
     *
     * @since    1.0.0
     * @param    int    $post_id    The post ID
     */
    public function save_meta_boxes($post_id) {
        // Verify nonce
        if (!isset($_POST['business_hierarchy_manager_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['business_hierarchy_manager_meta_box_nonce'], 'business_hierarchy_manager_meta_box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (isset($_POST['post_type'])) {
            if ($_POST['post_type'] === 'bureau_company' && !current_user_can('edit_bureau_company', $post_id)) {
                return;
            }
            if ($_POST['post_type'] === 'client_company' && !current_user_can('edit_client_company', $post_id)) {
                return;
            }
        }

        // Save meta fields
        if (isset($_POST['company_location'])) {
            update_post_meta($post_id, '_company_location', sanitize_text_field($_POST['company_location']));
        }

        if (isset($_POST['company_primary_member'])) {
            update_post_meta($post_id, '_company_primary_member', intval($_POST['company_primary_member']));
        }
    }

    /**
     * Filter admin queries to show only relevant data
     *
     * @since    1.0.0
     * @param    WP_Query    $query    The query object
     */
    public function filter_admin_queries($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $screen = get_current_screen();
        $current_user = wp_get_current_user();

        // Administrators can see everything - no filtering needed
        if (current_user_can('manage_options')) {
            return;
        }

        if ($screen && $screen->post_type === 'client_company') {
            // Filter client companies based on user permissions
            if (in_array('bureau_member', $current_user->roles) || in_array('bureau_primary', $current_user->roles)) {
                // Bureau members can see clients belonging to their bureau
                // This will be implemented when we have the core classes
            } elseif (in_array('client_primary', $current_user->roles) || in_array('client_member', $current_user->roles)) {
                // Client members can only see their own company
                // This will be implemented when we have the core classes
            }
        }

        if ($screen && $screen->post_type === 'bureau_company') {
            // Bureau members can only see their own bureau
            if (in_array('bureau_member', $current_user->roles) || in_array('bureau_primary', $current_user->roles)) {
                // This will be implemented when we have the core classes
            }
        }

        if ($screen && $screen->post_type === 'invitation') {
            // Filter invitations based on user permissions
            if (in_array('bureau_member', $current_user->roles) || in_array('bureau_primary', $current_user->roles)) {
                // Bureau members can see invitations for their clients
                // This will be implemented when we have the core classes
            } elseif (in_array('client_primary', $current_user->roles) || in_array('client_member', $current_user->roles)) {
                // Client members can only see their own invitations
                // This will be implemented when we have the core classes
            }
        }
    }

    /**
     * Map business meta capabilities
     *
     * @since    1.0.0
     * @param    array     $caps    The user's capabilities
     * @param    string    $cap     The capability being checked
     * @param    int       $user_id The user ID
     * @param    array     $args    Additional arguments
     * @return   array              The filtered capabilities
     */
    public function map_business_meta_caps($caps, $cap, $user_id, $args) {
        // Administrators can do everything
        if (user_can($user_id, 'manage_options')) {
            return array('manage_options');
        }

        // This will be implemented when we have the core classes
        // For now, return the original capabilities
        return $caps;
    }
}
