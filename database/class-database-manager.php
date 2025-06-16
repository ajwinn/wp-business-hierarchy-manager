<?php

/**
 * Database Manager Class
 *
 * Handles database operations
 *
 * @since      1.0.0
 * @package    Business_Hierarchy_Manager
 * @subpackage Business_Hierarchy_Manager/database
 */
class Business_Hierarchy_Manager_Database_Manager {

    /**
     * Initialize the class
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Initialize database manager
    }

    /**
     * Get table names
     *
     * @since    1.0.0
     * @return   array    Array of table names
     */
    public function get_table_names() {
        return get_option('business_hierarchy_manager_tables', array());
    }

    /**
     * Get table name by key
     *
     * @since    1.0.0
     * @param    string $key    Table key
     * @return   string         Table name
     */
    public function get_table_name($key) {
        $tables = $this->get_table_names();
        return isset($tables[$key]) ? $tables[$key] : '';
    }

    /**
     * Check if table exists
     *
     * @since    1.0.0
     * @param    string $table_name    Table name
     * @return   bool                  Whether table exists
     */
    public function table_exists($table_name) {
        global $wpdb;
        return $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
    }
}
