# Business Hierarchy Manager - Templating System

## Overview

The plugin now uses a proper templating system with opinionated file structures to render components. This ensures separation of concerns and makes the code more maintainable.

## File Structure

```
templates/
├── admin/
│   ├── components/
│   │   └── meta-boxes/
│   │       ├── bureau-details.php
│   │       └── primary-user.php
│   │   ├── partials/
│   │   │   ├── bureau-info-card.php
│   │   │   ├── form-actions.php
│   │   │   ├── form-header.php
│   │   │   ├── test-content.php
│   │   │   ├── test-footer.php
│   │   │   ├── test-header.php
│   │   │   └── user-info-card.php
│   │   ├── assets/
│   │   │   ├── admin.css
│   │   │   └── bureau-form.js
│   │   ├── bureau-form.php
│   │   ├── dashboard.php
│   │   ├── settings.php
│   │   └── test-page.php
│   ├── forms/
│   └── emails/
```

## Template Loading System

### Template Loader Class

The `Business_Hierarchy_Manager_Template_Loader` class handles template loading with data passing:

```php
// Load any template with data
business_hierarchy_manager_load_template('admin/bureau-form.php', $data);

// Load admin template
business_hierarchy_manager_load_admin_template('dashboard.php', $data);

// Load admin component
business_hierarchy_manager_load_admin_component('meta-boxes/bureau-details.php', $data);

// Load meta box template
business_hierarchy_manager_load_meta_box('bureau-details.php', $data);
```

### Data Passing

Templates receive data as variables:

```php
// In function
$data = array(
    'street1' => get_post_meta($post->ID, '_bureau_street1', true),
    'city' => get_post_meta($post->ID, '_bureau_city', true),
);

// Load template with data
business_hierarchy_manager_load_meta_box('bureau-details.php', $data);
```

```php
<!-- In template -->
<input type="text" name="bureau_street1" value="<?php echo esc_attr($street1); ?>" />
<input type="text" name="bureau_city" value="<?php echo esc_attr($city); ?>" />
```

## Component Structure

### Admin Pages

Admin pages are full templates that render complete pages:

- `dashboard.php` - Main admin dashboard
- `settings.php` - Settings page
- `bureau-form.php` - Add/Edit bureau form
- `test-page.php` - Test page for development

### Components

Components are reusable parts that can be included in multiple places:

- `meta-boxes/` - WordPress meta box templates
- `partials/` - Form sections and UI components

### Meta Boxes

Meta boxes are WordPress-specific components for post editing:

- `bureau-details.php` - Bureau company details
- `primary-user.php` - Primary user information

## Usage Examples

### Loading Admin Page

```php
function my_admin_page() {
    // Enqueue assets
    wp_enqueue_style('my-admin-styles', ...);
    
    // Load template with data
    business_hierarchy_manager_load_admin_template('my-page.php', array(
        'title' => 'My Page',
        'data' => $some_data,
    ));
}
```

### Loading Meta Box

```php
function my_meta_box_callback($post) {
    // Get data
    $data = array(
        'field1' => get_post_meta($post->ID, '_field1', true),
        'field2' => get_post_meta($post->ID, '_field2', true),
    );
    
    // Load template
    business_hierarchy_manager_load_meta_box('my-meta-box.php', $data);
}
```

### Template Structure

```php
<?php
/**
 * Template Name
 * 
 * @package BusinessHierarchyManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Data variables (passed from function)
$field1 = isset($field1) ? $field1 : '';
$field2 = isset($field2) ? $field2 : '';
?>

<div class="my-component">
    <h2><?php echo esc_html($field1); ?></h2>
    <p><?php echo esc_html($field2); ?></p>
</div>
```

## Benefits

1. **Separation of Concerns** - HTML is separate from PHP logic
2. **Reusability** - Components can be used in multiple places
3. **Maintainability** - Easy to find and modify templates
4. **Consistency** - Standardized structure across the plugin
5. **Data Safety** - Proper data passing and escaping

## Best Practices

1. **Always escape output** using `esc_html()`, `esc_attr()`, etc.
2. **Check for data existence** before using variables
3. **Use descriptive template names** that indicate their purpose
4. **Keep templates focused** on presentation, not logic
5. **Pass data explicitly** rather than using global variables 