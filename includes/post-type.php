<?php
// Register CPT only for storage — NOT visible in admin
add_action('init', function() {

    register_post_type('advanced_loop_filter', [
        'labels' => [
            'name' => 'Loop Filter Settings',
        ],
        'public'        => false,   // Not publicly queryable
        'show_ui'       => false,   // Hides CPT from admin menu
        'show_in_menu'  => false,   // DEFINITELY hide menu
        'supports'      => ['title'],
    ]);

});

// Save settings via Settings Page
add_action('admin_init', function () {

    // Only trigger on your settings page
    if (!isset($_POST['algf_heading_type'])) return;

    // Verify nonce
    if (!check_admin_referer('algf_save_settings')) return;

    // Fetch the one settings post
    $post = get_posts([
        'post_type'      => 'advanced_loop_filter',
        'posts_per_page' => 1
    ]);

    if (empty($post)) return;

    $post_id = $post[0]->ID;

    // Save fields
    update_post_meta($post_id, '_algf_heading_type', sanitize_text_field($_POST['algf_heading_type']));
    update_post_meta($post_id, '_algf_class_selector', sanitize_text_field($_POST['algf_class_selector']));

    if ($_POST['algf_class_selector'] === 'custom') {
        update_post_meta($post_id, '_algf_custom_class', sanitize_text_field($_POST['algf_custom_class']));
    }
});

