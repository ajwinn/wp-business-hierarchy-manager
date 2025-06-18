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

<div class="card bg-base-100 shadow-lg rounded-xl w-full mb-8">
    <div class="card-body p-8">
        <h3 class="card-title text-2xl mb-2 text-left">Primary User</h3>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="primary_first_name">
                <span class="label-text">First Name</span>
            </label>
            <input type="text" id="primary_first_name" name="primary_first_name" value="<?php echo esc_attr($primary_first_name); ?>" class="input input-bordered w-full" placeholder="Enter first name" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="primary_last_name">
                <span class="label-text">Last Name</span>
            </label>
            <input type="text" id="primary_last_name" name="primary_last_name" value="<?php echo esc_attr($primary_last_name); ?>" class="input input-bordered w-full" placeholder="Enter last name" />
        </div>
        <div class="form-control w-full">
            <label class="label text-left" for="primary_email">
                <span class="label-text">Email Address</span>
            </label>
            <input type="email" id="primary_email" name="primary_email" value="<?php echo esc_attr($primary_email); ?>" class="input input-bordered w-full" placeholder="user@example.com" />
            <span class="text-xs text-base-content/60 mt-1">This email will be used to create the primary user account</span>
        </div>
    </div>
</div> 