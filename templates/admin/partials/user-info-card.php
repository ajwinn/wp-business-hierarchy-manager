<?php
/**
 * Primary User Information Card Partial
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="card bg-base-100 shadow-lg rounded-xl w-full h-full min-h-[600px]">
    <div class="card-body p-10 flex flex-col h-full">
        <h3 class="card-title text-2xl mb-2 text-left">Primary User Information</h3>
        <p class="text-sm text-base-content/70 mb-6 text-left flex-grow-0">Enter the primary user details for this bureau.</p>
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="form-control w-full">
                <label class="label text-left" for="primary_first_name">
                    <span class="label-text">First Name *</span>
                </label>
                <input class="input input-bordered w-full" id="primary_first_name" name="primary_first_name" required placeholder="Enter first name" />
            </div>
            <div class="form-control w-full">
                <label class="label text-left" for="primary_last_name">
                    <span class="label-text">Last Name *</span>
                </label>
                <input class="input input-bordered w-full" id="primary_last_name" name="primary_last_name" required placeholder="Enter last name" />
            </div>
        </div>
        <div class="form-control w-full">
            <label class="label text-left" for="primary_email">
                <span class="label-text">Email Address *</span>
            </label>
            <input class="input input-bordered w-full" type="email" id="primary_email" name="primary_email" required placeholder="user@example.com" />
            <span class="text-xs text-base-content/60 mt-1">This email will be used to create the primary user account</span>
        </div>
    </div>
</div> 