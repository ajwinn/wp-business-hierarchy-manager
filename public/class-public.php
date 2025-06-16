<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/public
 */
class Business_Hierarchy_Manager_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            BUSINESS_HIERARCHY_MANAGER_URL . 'public/css/public.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            BUSINESS_HIERARCHY_MANAGER_URL . 'public/js/public.js',
            array('jquery'),
            $this->version,
            false
        );

        // Localize script for AJAX
        wp_localize_script($this->plugin_name, 'business_hierarchy_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('business_hierarchy_nonce'),
        ));
    }

    /**
     * Register shortcodes
     *
     * @since    1.0.0
     */
    public function register_shortcodes() {
        add_shortcode('business_hierarchy_invitation_form', array($this, 'invitation_form_shortcode'));
        add_shortcode('business_hierarchy_onboarding_form', array($this, 'onboarding_form_shortcode'));
    }

    /**
     * Invitation form shortcode
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes
     * @return   string            Shortcode output
     */
    public function invitation_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'company_id' => 0,
            'company_type' => 'client',
        ), $atts, 'business_hierarchy_invitation_form');

        ob_start();
        include BUSINESS_HIERARCHY_MANAGER_PATH . 'public/partials/invitation-form.php';
        return ob_get_clean();
    }

    /**
     * Onboarding form shortcode
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes
     * @return   string            Shortcode output
     */
    public function onboarding_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'token' => '',
        ), $atts, 'business_hierarchy_onboarding_form');

        ob_start();
        include BUSINESS_HIERARCHY_MANAGER_PATH . 'public/partials/onboarding-form.php';
        return ob_get_clean();
    }

    /**
     * Handle invitation AJAX request
     *
     * @since    1.0.0
     */
    public function handle_invitation() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'business_hierarchy_nonce')) {
            wp_die(__('Security check failed', 'business-hierarchy-manager'));
        }

        // Check permissions
        if (!current_user_can('send_invitations')) {
            wp_die(__('You do not have permission to send invitations', 'business-hierarchy-manager'));
        }

        // Validate input
        $email = sanitize_email($_POST['email']);
        $company_id = intval($_POST['company_id']);
        $company_type = sanitize_text_field($_POST['company_type']);
        $role = sanitize_text_field($_POST['role']);

        if (empty($email) || empty($company_id) || empty($company_type) || empty($role)) {
            wp_send_json_error(__('All fields are required', 'business-hierarchy-manager'));
        }

        // This will be implemented when we have the invitation manager
        // For now, just return success
        wp_send_json_success(__('Invitation sent successfully', 'business-hierarchy-manager'));
    }
}
