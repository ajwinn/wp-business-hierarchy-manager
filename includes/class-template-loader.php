<?php
/**
 * Template Loader Class
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Business_Hierarchy_Manager_Template_Loader {
    
    /**
     * Plugin template directory
     */
    private $template_dir;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->template_dir = plugin_dir_path(dirname(__FILE__)) . 'templates/';
    }
    
    /**
     * Load a template with data
     * 
     * @param string $template_path Relative path to template
     * @param array $data Data to pass to template
     * @param bool $echo Whether to echo or return the output
     * @return string|void
     */
    public function load_template($template_path, $data = array(), $echo = true) {
        $full_path = $this->template_dir . $template_path;
        
        if (!file_exists($full_path)) {
            error_log("Template not found: {$full_path}");
            return '';
        }
        
        // Extract data to make variables available in template
        if (!empty($data)) {
            extract($data);
        }
        
        // Start output buffering
        ob_start();
        
        // Include the template
        include $full_path;
        
        // Get the output
        $output = ob_get_clean();
        
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }
    
    /**
     * Load admin template
     * 
     * @param string $template_name Template name
     * @param array $data Data to pass to template
     * @param bool $echo Whether to echo or return the output
     * @return string|void
     */
    public function load_admin_template($template_name, $data = array(), $echo = true) {
        return $this->load_template('admin/' . $template_name, $data, $echo);
    }
    
    /**
     * Load admin component
     * 
     * @param string $component_name Component name
     * @param array $data Data to pass to component
     * @param bool $echo Whether to echo or return the output
     * @return string|void
     */
    public function load_admin_component($component_name, $data = array(), $echo = true) {
        return $this->load_template('admin/components/' . $component_name, $data, $echo);
    }
    
    /**
     * Load meta box template
     * 
     * @param string $meta_box_name Meta box name
     * @param array $data Data to pass to template
     * @param bool $echo Whether to echo or return the output
     * @return string|void
     */
    public function load_meta_box($meta_box_name, $data = array(), $echo = true) {
        return $this->load_template('admin/components/meta-boxes/' . $meta_box_name, $data, $echo);
    }
} 