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

<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Primary User Information</h3>
        <p class="text-sm text-muted-foreground">Enter the primary user details for this bureau.</p>
    </div>
    <div class="p-6 pt-0 space-y-4">
        <!-- Name Fields Row -->
        <div class="grid grid-cols-2 gap-4">
            <div class="grid w-full items-center gap-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="primary_first_name">
                    First Name *
                </label>
                <input 
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                    id="primary_first_name" 
                    name="primary_first_name" 
                    required 
                    placeholder="Enter first name"
                />
            </div>
            <div class="grid w-full items-center gap-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="primary_last_name">
                    Last Name *
                </label>
                <input 
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                    id="primary_last_name" 
                    name="primary_last_name" 
                    required 
                    placeholder="Enter last name"
                />
            </div>
        </div>
        
        <!-- Email Field -->
        <div class="grid w-full max-w-sm items-center gap-1.5">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="primary_email">
                Email Address *
            </label>
            <input 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                type="email" 
                id="primary_email" 
                name="primary_email" 
                required 
                placeholder="user@example.com"
            />
            <p class="text-sm text-muted-foreground">This email will be used to create the primary user account</p>
        </div>
    </div>
</div> 