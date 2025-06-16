<?php

/**
 * The main plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/includes
 */
class Business_Hierarchy_Manager {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Business_Hierarchy_Manager_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('BUSINESS_HIERARCHY_MANAGER_VERSION')) {
            $this->version = BUSINESS_HIERARCHY_MANAGER_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'business-hierarchy-manager';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_core_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Business_Hierarchy_Manager_Loader. Orchestrates the hooks of the plugin.
     * - Business_Hierarchy_Manager_i18n. Defines internationalization functionality.
     * - Business_Hierarchy_Manager_Admin. Defines all hooks for the admin area.
     * - Business_Hierarchy_Manager_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'includes/class-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'includes/class-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'admin/class-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'public/class-public.php';

        /**
         * Core business logic classes
         */
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'core/class-company-manager.php';
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'core/class-user-company-manager.php';
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'core/class-permission-manager.php';
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'core/class-invitation-manager.php';
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'core/class-settings-manager.php';

        /**
         * Database management
         */
        require_once BUSINESS_HIERARCHY_MANAGER_PATH . 'database/class-database-manager.php';

        $this->loader = new Business_Hierarchy_Manager_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Business_Hierarchy_Manager_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new Business_Hierarchy_Manager_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Business_Hierarchy_Manager_Admin($this->get_plugin_name(), $this->get_version());

        // Admin scripts and styles
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Admin menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        // Custom post types
        $this->loader->add_action('init', $plugin_admin, 'register_post_types');
        $this->loader->add_action('init', $plugin_admin, 'register_user_roles');
        $this->loader->add_action('init', $plugin_admin, 'register_capabilities');

        // Meta boxes
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_boxes');
        $this->loader->add_action('save_post', $plugin_admin, 'save_meta_boxes');

        // Admin filters
        $this->loader->add_action('pre_get_posts', $plugin_admin, 'filter_admin_queries');
        $this->loader->add_filter('map_meta_cap', $plugin_admin, 'map_business_meta_caps', 10, 4);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Business_Hierarchy_Manager_Public($this->get_plugin_name(), $this->get_version());

        // Public scripts and styles
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // Shortcodes
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');

        // AJAX handlers
        $this->loader->add_action('wp_ajax_handle_invitation', $plugin_public, 'handle_invitation');
        $this->loader->add_action('wp_ajax_nopriv_handle_invitation', $plugin_public, 'handle_invitation');
    }

    /**
     * Register all of the hooks related to core functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_core_hooks() {
        // Initialize core managers
        $this->loader->add_action('init', $this, 'initialize_core_managers');

        // User registration and profile hooks
        $this->loader->add_action('user_register', $this, 'handle_user_registration');
        $this->loader->add_action('show_user_profile', $this, 'add_user_profile_fields');
        $this->loader->add_action('edit_user_profile', $this, 'add_user_profile_fields');
        $this->loader->add_action('personal_options_update', $this, 'save_user_profile_fields');
        $this->loader->add_action('edit_user_profile_update', $this, 'save_user_profile_fields');
    }

    /**
     * Initialize core managers
     *
     * @since    1.0.0
     */
    public function initialize_core_managers() {
        // Initialize core managers as needed
        $this->company_manager = new Business_Hierarchy_Manager_Company_Manager();
        $this->user_company_manager = new Business_Hierarchy_Manager_User_Company_Manager();
        $this->permission_manager = new Business_Hierarchy_Manager_Permission_Manager();
        $this->invitation_manager = new Business_Hierarchy_Manager_Invitation_Manager();
        $this->settings_manager = new Business_Hierarchy_Manager_Settings_Manager();
    }

    /**
     * Handle user registration
     *
     * @since    1.0.0
     * @param    int    $user_id    The user ID
     */
    public function handle_user_registration($user_id) {
        // Handle any special logic for new user registration
        // This could include setting default company associations, etc.
    }

    /**
     * Add custom fields to user profile
     *
     * @since    1.0.0
     * @param    WP_User    $user    The user object
     */
    public function add_user_profile_fields($user) {
        // Add company association fields to user profile
        include BUSINESS_HIERARCHY_MANAGER_PATH . 'admin/partials/user-profile-fields.php';
    }

    /**
     * Save custom user profile fields
     *
     * @since    1.0.0
     * @param    int    $user_id    The user ID
     */
    public function save_user_profile_fields($user_id) {
        // Save company association data
        if (current_user_can('edit_user', $user_id)) {
            if (isset($_POST['company_id']) && isset($_POST['company_type'])) {
                $this->user_company_manager->assign_user_to_company(
                    $user_id,
                    intval($_POST['company_id']),
                    sanitize_text_field($_POST['company_type'])
                );
            }
        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Business_Hierarchy_Manager_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
