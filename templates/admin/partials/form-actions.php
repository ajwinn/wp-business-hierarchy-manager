<?php
/**
 * Form Actions Partial
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="flex items-center gap-4 mt-4 w-full">
    <button type="submit" class="btn btn-soft btn-primary">
        Create Bureau Company
    </button>
    <a href="<?php echo admin_url('edit.php?post_type=bureau_company'); ?>" class="btn btn-outline">
        Cancel
    </a>
</div>

<button class="btn btn-primary btn-soft" style="display:none">Dummy</button> 