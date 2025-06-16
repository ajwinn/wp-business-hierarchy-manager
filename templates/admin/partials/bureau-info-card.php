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

<div class="rounded-lg border bg-card text-card-foreground shadow-sm">
    <div class="flex flex-col space-y-1.5 p-6">
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Bureau Information</h3>
        <p class="text-sm text-muted-foreground">Enter the bureau company details.</p>
    </div>
    <div class="p-6 pt-0 space-y-4">
        <!-- Bureau Name -->
        <div class="grid w-full max-w-sm items-center gap-1.5">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="post_title">
                Bureau Company Name *
            </label>
            <input 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                id="post_title" 
                name="post_title" 
                required 
                placeholder="Enter bureau company name"
            />
        </div>
        
        <!-- Street Address 1 -->
        <div class="grid w-full max-w-sm items-center gap-1.5">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="bureau_street1">
                Street Address 1
            </label>
            <input 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                id="bureau_street1" 
                name="bureau_street1" 
                placeholder="123 Main Street"
            />
        </div>
        
        <!-- Street Address 2 -->
        <div class="grid w-full max-w-sm items-center gap-1.5">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="bureau_street2">
                Street Address 2
            </label>
            <input 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                id="bureau_street2" 
                name="bureau_street2" 
                placeholder="Suite 100, Floor 2, etc."
            />
        </div>
        
        <!-- City, State, ZIP Row -->
        <div class="grid grid-cols-3 gap-4">
            <div class="grid w-full items-center gap-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="bureau_city">
                    City
                </label>
                <input 
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                    id="bureau_city" 
                    name="bureau_city" 
                    placeholder="City"
                />
            </div>
            <div class="grid w-full items-center gap-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="bureau_state">
                    State
                </label>
                <input 
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                    id="bureau_state" 
                    name="bureau_state" 
                    placeholder="State"
                />
            </div>
            <div class="grid w-full items-center gap-1.5">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="bureau_zip">
                    ZIP Code
                </label>
                <input 
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                    id="bureau_zip" 
                    name="bureau_zip" 
                    placeholder="12345"
                />
            </div>
        </div>
        
        <!-- Phone Number -->
        <div class="grid w-full max-w-sm items-center gap-1.5">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="bureau_phone">
                Phone Number
            </label>
            <input 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                type="tel" 
                id="bureau_phone" 
                name="bureau_phone" 
                placeholder="(555) 123-4567"
            />
        </div>
    </div>
</div> 