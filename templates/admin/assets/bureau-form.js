/**
 * Bureau Form JavaScript
 * 
 * @package BusinessHierarchyManager
 */

jQuery(document).ready(function($) {
    // Handle form submission
    $('#bureau-company-form').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.text('Creating...').prop('disabled', true);
        
        // Submit the form
        this.submit();
    });
    
    // Add some basic form validation
    $('#bureau-company-form input[required]').on('blur', function() {
        var $field = $(this);
        var $label = $field.siblings('label');
        
        if (!$field.val()) {
            $field.addClass('border-destructive');
            if (!$label.find('.error-message').length) {
                $label.append('<span class="error-message text-sm text-destructive">This field is required</span>');
            }
        } else {
            $field.removeClass('border-destructive');
            $label.find('.error-message').remove();
        }
    });
    
    // Email validation
    $('#primary_email').on('blur', function() {
        var $field = $(this);
        var email = $field.val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $field.addClass('border-destructive');
            var $label = $field.siblings('label');
            if (!$label.find('.error-message').length) {
                $label.append('<span class="error-message text-sm text-destructive">Please enter a valid email address</span>');
            }
        } else {
            $field.removeClass('border-destructive');
            $field.siblings('label').find('.error-message').remove();
        }
    });
}); 