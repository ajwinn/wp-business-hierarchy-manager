<?php
/**
 * Primary User Meta Box Template
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Data should be passed from the calling function
$primary_first_name = isset($primary_first_name) ? $primary_first_name : '';
$primary_last_name = isset($primary_last_name) ? $primary_last_name : '';
$primary_email = isset($primary_email) ? $primary_email : '';
?>

<div class="bureau-form">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="primary_first_name">First Name</label>
            </th>
            <td>
                <input type="text" id="primary_first_name" name="primary_first_name" value="<?php echo esc_attr($primary_first_name); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="primary_last_name">Last Name</label>
            </th>
            <td>
                <input type="text" id="primary_last_name" name="primary_last_name" value="<?php echo esc_attr($primary_last_name); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="primary_email">Email</label>
            </th>
            <td>
                <input type="email" id="primary_email" name="primary_email" value="<?php echo esc_attr($primary_email); ?>" class="regular-text" />
                <p class="description">This email will be used to create the primary user account</p>
            </td>
        </tr>
    </table>
</div> 