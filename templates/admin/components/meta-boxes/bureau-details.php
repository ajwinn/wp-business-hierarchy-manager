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

<div class="card bg-base-100 shadow-lg rounded-xl w-full mb-8">
    <div class="card-body p-8">
        <h3 class="card-title text-2xl mb-2 text-left">Bureau Details</h3>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_street1">
                <span class="label-text">Street Address 1</span>
            </label>
            <input type="text" id="bureau_street1" name="bureau_street1" value="<?php echo esc_attr($street1); ?>" class="input input-bordered w-full" placeholder="123 Main Street" />
            <span class="text-xs text-base-content/60 mt-1">Enter the primary street address</span>
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_street2">
                <span class="label-text">Street Address 2</span>
            </label>
            <input type="text" id="bureau_street2" name="bureau_street2" value="<?php echo esc_attr($street2); ?>" class="input input-bordered w-full" placeholder="Suite 100, Floor 2, etc." />
            <span class="text-xs text-base-content/60 mt-1">Suite, floor, or additional address information</span>
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_city">
                <span class="label-text">City</span>
            </label>
            <input type="text" id="bureau_city" name="bureau_city" value="<?php echo esc_attr($city); ?>" class="input input-bordered w-full" placeholder="City" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_state">
                <span class="label-text">State</span>
            </label>
            <input type="text" id="bureau_state" name="bureau_state" value="<?php echo esc_attr($state); ?>" class="input input-bordered w-full" placeholder="State" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_zip">
                <span class="label-text">ZIP Code</span>
            </label>
            <input type="text" id="bureau_zip" name="bureau_zip" value="<?php echo esc_attr($zip); ?>" class="input input-bordered w-full" placeholder="12345" />
        </div>
        <div class="form-control w-full">
            <label class="label text-left" for="bureau_phone">
                <span class="label-text">Phone Number</span>
            </label>
            <input type="tel" id="bureau_phone" name="bureau_phone" value="<?php echo esc_attr($phone); ?>" class="input input-bordered w-full" placeholder="(555) 123-4567" />
            <span class="text-xs text-base-content/60 mt-1">Enter the bureau's phone number</span>
        </div>
    </div>
</div> 