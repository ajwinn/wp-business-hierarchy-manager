<?php
/**
 * Bureau Details Meta Box Template
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Data should be passed from the calling function
$street1 = isset($street1) ? $street1 : '';
$street2 = isset($street2) ? $street2 : '';
$city = isset($city) ? $city : '';
$state = isset($state) ? $state : '';
$zip = isset($zip) ? $zip : '';
$phone = isset($phone) ? $phone : '';
?>

<div class="bureau-form">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="bureau_street1">Street Address 1</label>
            </th>
            <td>
                <input type="text" id="bureau_street1" name="bureau_street1" value="<?php echo esc_attr($street1); ?>" class="regular-text" />
                <p class="description">Enter the primary street address</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="bureau_street2">Street Address 2</label>
            </th>
            <td>
                <input type="text" id="bureau_street2" name="bureau_street2" value="<?php echo esc_attr($street2); ?>" class="regular-text" />
                <p class="description">Suite, floor, or additional address information</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="bureau_city">City</label>
            </th>
            <td>
                <input type="text" id="bureau_city" name="bureau_city" value="<?php echo esc_attr($city); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="bureau_state">State</label>
            </th>
            <td>
                <input type="text" id="bureau_state" name="bureau_state" value="<?php echo esc_attr($state); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="bureau_zip">ZIP Code</label>
            </th>
            <td>
                <input type="text" id="bureau_zip" name="bureau_zip" value="<?php echo esc_attr($zip); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="bureau_phone">Phone</label>
            </th>
            <td>
                <input type="tel" id="bureau_phone" name="bureau_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" />
                <p class="description">Enter the bureau's phone number</p>
            </td>
        </tr>
    </table>
</div> 