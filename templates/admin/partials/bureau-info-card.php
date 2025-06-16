<?php
/**
 * Bureau Information Card Partial
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="card bg-base-100 shadow-lg rounded-xl w-full mb-8">
    <div class="card-body p-8">
        <h3 class="card-title text-2xl mb-2 text-left">Bureau Information</h3>
        <p class="text-sm text-base-content/70 mb-6 text-left">Enter the bureau company details.</p>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="post_title">
                <span class="label-text">Bureau Company Name *</span>
            </label>
            <input class="input input-bordered w-full" id="post_title" name="post_title" required placeholder="Enter bureau company name" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_street1">
                <span class="label-text">Street Address 1</span>
            </label>
            <input class="input input-bordered w-full" id="bureau_street1" name="bureau_street1" placeholder="123 Main Street" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_street2">
                <span class="label-text">Street Address 2</span>
            </label>
            <input class="input input-bordered w-full" id="bureau_street2" name="bureau_street2" placeholder="Suite 100, Floor 2, etc." />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_city">
                <span class="label-text">City</span>
            </label>
            <input class="input input-bordered w-full" id="bureau_city" name="bureau_city" placeholder="City" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_state">
                <span class="label-text">State</span>
            </label>
            <input class="input input-bordered w-full" id="bureau_state" name="bureau_state" placeholder="State" />
        </div>
        <div class="form-control mb-6 w-full">
            <label class="label text-left" for="bureau_zip">
                <span class="label-text">ZIP Code</span>
            </label>
            <input class="input input-bordered w-full" id="bureau_zip" name="bureau_zip" placeholder="12345" />
        </div>
        <div class="form-control w-full">
            <label class="label text-left" for="bureau_phone">
                <span class="label-text">Phone Number</span>
            </label>
            <input class="input input-bordered w-full" type="tel" id="bureau_phone" name="bureau_phone" placeholder="(555) 123-4567" />
        </div>
    </div>
</div> 