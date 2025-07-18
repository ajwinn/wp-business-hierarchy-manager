<?php
/**
 * Test Content Partial
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
        <h3 class="text-2xl font-semibold leading-none tracking-tight">Test Card</h3>
        <p class="text-sm text-muted-foreground">This card demonstrates the shadcn/ui styling system.</p>
    </div>
    <div class="p-6 pt-0 space-y-4">
        <div class="grid w-full max-w-sm items-center gap-1.5">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="test_input">
                Test Input Field
            </label>
            <input 
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                id="test_input" 
                name="test_input" 
                placeholder="Enter some text..."
            />
        </div>
        
        <div class="flex items-center gap-4">
            <button 
                type="button" 
                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
            >
                Primary Button
            </button>
            <button 
                type="button" 
                class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
            >
                Secondary Button
            </button>
        </div>
    </div>
</div> 