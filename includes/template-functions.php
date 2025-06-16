<?php
/**
 * Template Helper Functions
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Global template loader instance
 */
global $business_hierarchy_manager_template_loader;

/**
 * Initialize template loader
 */
function business_hierarchy_manager_init_template_loader() {
    global $business_hierarchy_manager_template_loader;
    
    if (!class_exists('Business_Hierarchy_Manager_Template_Loader')) {
        require_once plugin_dir_path(__FILE__) . 'class-template-loader.php';
    }
    
    $business_hierarchy_manager_template_loader = new Business_Hierarchy_Manager_Template_Loader();
}
add_action('init', 'business_hierarchy_manager_init_template_loader');

/**
 * Load a template
 * 
 * @param string $template_path Template path
 * @param array $data Template data
 * @param bool $echo Whether to echo output
 * @return string|void
 */
function business_hierarchy_manager_load_template($template_path, $data = array(), $echo = true) {
    global $business_hierarchy_manager_template_loader;
    
    if (!$business_hierarchy_manager_template_loader) {
        business_hierarchy_manager_init_template_loader();
    }
    
    return $business_hierarchy_manager_template_loader->load_template($template_path, $data, $echo);
}

/**
 * Load an admin template
 * 
 * @param string $template_name Template name
 * @param array $data Template data
 * @param bool $echo Whether to echo output
 * @return string|void
 */
function business_hierarchy_manager_load_admin_template($template_name, $data = array(), $echo = true) {
    global $business_hierarchy_manager_template_loader;
    
    if (!$business_hierarchy_manager_template_loader) {
        business_hierarchy_manager_init_template_loader();
    }
    
    return $business_hierarchy_manager_template_loader->load_admin_template($template_name, $data, $echo);
}

/**
 * Load an admin component
 * 
 * @param string $component_name Component name
 * @param array $data Component data
 * @param bool $echo Whether to echo output
 * @return string|void
 */
function business_hierarchy_manager_load_admin_component($component_name, $data = array(), $echo = true) {
    global $business_hierarchy_manager_template_loader;
    
    if (!$business_hierarchy_manager_template_loader) {
        business_hierarchy_manager_init_template_loader();
    }
    
    return $business_hierarchy_manager_template_loader->load_admin_component($component_name, $data, $echo);
}

/**
 * Load a meta box template
 * 
 * @param string $meta_box_name Meta box name
 * @param array $data Meta box data
 * @param bool $echo Whether to echo output
 * @return string|void
 */
function business_hierarchy_manager_load_meta_box($meta_box_name, $data = array(), $echo = true) {
    global $business_hierarchy_manager_template_loader;
    
    if (!$business_hierarchy_manager_template_loader) {
        business_hierarchy_manager_init_template_loader();
    }
    
    return $business_hierarchy_manager_template_loader->load_meta_box($meta_box_name, $data, $echo);
} 