<?php
/**
 * Plugin Name:       Loop Grid Search & Sort
 * Plugin URI:        https://hassanalishoaib.com/plugins/elementor-loop-filter
 * Description:       Adds a stylish search and sort filter for Elementor Loop Grid widgets using the shortcode [elementor_loop_filter]. Fully multilingual (English/Arabic) and theme-friendly.
 * Version:           1.2
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            Hassan Ali Shoaib
 * Author URI:        https://hassanalishoaib.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       elementor-loop-filter
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ELF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ELF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Load main includes
require_once ELF_PLUGIN_PATH . 'includes/shortcode.php';
require_once ELF_PLUGIN_PATH . 'includes/hooks.php';
require_once ELF_PLUGIN_PATH . 'includes/post-type.php';  // Load CPT first


// Enqueue assets
add_action( 'wp_enqueue_scripts', function() {

    // Load saved selector from CPT
    $selector_setting = '';
    $filter_post = get_posts([
        'post_type' => 'advanced_loop_filter',
        'posts_per_page' => 1
    ]);

    if (!empty($filter_post)) {
      $heading = get_post_meta($filter_post[0]->ID, '_algf_heading_type', true);
      $class   = get_post_meta($filter_post[0]->ID, '_algf_class_selector', true);
      $custom  = get_post_meta($filter_post[0]->ID, '_algf_custom_class', true);

if ($class === 'custom' && !empty($custom)) {
    $selector_setting = $custom;
} else {
    // Combine heading + class
    $selector_setting = "$heading, $class";
}

    }

    wp_enqueue_style(
        'elementor-loop-filter-style',
        ELF_PLUGIN_URL . 'assets/css/style.css',
        [],
        '1.1.0'
    );

    wp_enqueue_script(
        'elementor-loop-filter-script',
        ELF_PLUGIN_URL . 'assets/js/main.js',
        ['jquery'],
        '1.1.0',
        true
    );

    // Send value to JS
    wp_localize_script('elementor-loop-filter-script', 'LoopFilterSettings', [
        'titleSelectors' => $selector_setting ?: 'h6, h3, .elementor-post__title'
    ]);
});



// ✅ Register Elementor Widget
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    require_once ELF_PLUGIN_PATH . 'includes/widget-loop-filter.php';
    $widgets_manager->register( new \Elementor_Loop_Filter_Widget() );
});

add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category(
        'loop-advanced-filter',
        [
            'title' => __( 'Loop Advanced Filter', 'elementor-loop-filter' ),
            'icon'  => 'fa fa-filter', // You can use ANY FontAwesome or Elementor icon
        ]
    );
});

register_activation_hook(__FILE__, function() {

    if (!get_posts(['post_type' => 'advanced_loop_filter'])) {
        $id = wp_insert_post([
            'post_type'   => 'advanced_loop_filter',
            'post_status' => 'publish',
            'post_title'  => 'Loop Filter Settings'
        ]);
    }
});

// Completely remove "Add New" menu link for this CPT
add_action('admin_menu', function() {
    remove_submenu_page('edit.php?post_type=advanced_loop_filter', 'post-new.php?post_type=advanced_loop_filter');
});


add_action('admin_menu', function() {
    add_menu_page(
        'Loop Grid Filter Settings',
        'Loop Grid Filter',
        'manage_options',
        'loop-grid-filter-settings',
        'algf_render_settings_page',
        'dashicons-filter',
        26
    );
});

function algf_render_settings_page() {

    $post = get_posts([
        'post_type' => 'advanced_loop_filter',
        'posts_per_page' => 1
    ]);

    $post_id = $post ? $post[0]->ID : 0;

    // Load saved meta
    $saved_heading = get_post_meta($post_id, '_algf_heading_type', true);
    $saved_class = get_post_meta($post_id, '_algf_class_selector', true);
    $saved_custom_class = get_post_meta($post_id, '_algf_custom_class', true);

    // Default values
    $heading_options = ['h1','h2','h3','h4','h5','h6','p'];
    $class_options   = ['.elementor-post__title', '.post-title', '.card-title', 'custom'];
    ?>

    <div class="wrap">
        <h1>Loop Grid Filter Settings</h1>

        <?php
        // -------------------------
        // 🔥 CodeWorld360 Branding Banner
        // -------------------------

        $logo_url = plugin_dir_url(__FILE__) . 'assets/logo.png'; // put logo inside /assets/
        ?>

        <style>
/* Branding Banner */
.cw360-banner {
    margin-top: 20px;
    margin-bottom: 30px;
    padding: 25px;
    border-radius: 12px;
    background: linear-gradient(135deg, #0f172a, #1e293b, #0ea5e9);
    color: #fff;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: glowBorder 3s infinite alternate;
}

@keyframes glowBorder {
    from { box-shadow: 0 0 10px #0ea5e9; }
    to   { box-shadow: 0 0 25px #38bdf8; }
}

.cw360-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.cw360-left img {
    width: 80px;
    height: auto;
    filter: drop-shadow(0 0 5px rgba(255,255,255,0.2));
}

.cw360-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.cw360-sub {
    font-size: 14px;
    opacity: 0.8;
    margin-top: 4px;
}

.cw360-chip {
    background: rgba(255,255,255,0.15);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    margin-bottom: 10px;
    display: inline-block;
    text-align: right;
}

/* Buttons */
.cw360-cta a {
    background: #ffffff22;
    padding: 10px 18px;
    border-radius: 8px;
    color: #fff !important;
    font-weight: 500;
    text-decoration: none;
    margin-left: 10px;
    transition: 0.2s ease;
    border: 1px solid #ffffff33;
}

.cw360-cta a:hover {
    background: #ffffff33;
    transform: translateY(-2px);
}

/* Social Links */
.cw360-social {
    margin-top: 8px;
    opacity: 0.85;
    font-size: 13px;
    text-align: right;
}

.cw360-social a {
    color: #fff;
    text-decoration: underline;
    margin-left: 8px;
}

/* Dark Mode Support */
body.wp-admin.theme-dark .cw360-banner {
    background: linear-gradient(135deg, #0d0d0d, #1a1a1a, #333);
}

</style>


        <div class="cw360-banner">
            <div class="cw360-left">
                <img src="<?php echo esc_url($logo_url); ?>" alt="CodeWorld360 Logo" />
                <div>
                    <div class="cw360-title">Loop Grid Filter</div>
                    <div class="cw360-sub">Built by <strong>CodeWorld360</strong>. Fast & beautiful UI.</div>
                </div>
            </div>

            <div class="cw360-right">
                <div class="cw360-chip">v 1.2</div>

                <div class="cw360-cta">
                    <a class="cw360-btn" href="https://www.paypal.com/donate" target="_blank">Sponsor</a>
                    <a class="cw360-flat" href="https://codeworld360.com" target="_blank">Website</a>
                </div>

                <div class="cw360-social">
                    <a href="https://github.com/CodeWorld360" target="_blank">GH</a>
                    <a href="mailto:support@codeworld360.com">Support</a>
                </div>
            </div>
        </div>

        <!-- ---------------- END BANNER ---------------- -->

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

            <?php wp_nonce_field('algf_save_settings'); ?>
            <input type="hidden" name="action" value="algf_save_settings">

            <table class="form-table">

                <tr>
                    <th>Heading Type</th>
                    <td>
                        <select name="algf_heading_type">
                            <?php foreach ($heading_options as $tag): ?>
                                <option value="<?php echo $tag; ?>" <?php selected($saved_heading, $tag); ?>>
                                    <?php echo strtoupper($tag); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Class Selector</th>
                    <td>
                        <select id="algf_class_selector" name="algf_class_selector">
                            <?php foreach ($class_options as $class): ?>
                                <option value="<?php echo $class; ?>" <?php selected($saved_class, $class); ?>>
                                    <?php echo ($class === 'custom') ? 'Custom Class' : $class; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr id="algf_custom_class_row" style="<?php echo ($saved_class === 'custom') ? '' : 'display:none'; ?>">
                    <th>Enter Custom Class</th>
                    <td>
                        <input type="text"
                               name="algf_custom_class"
                               value="<?php echo esc_attr($saved_custom_class); ?>"
                               placeholder=".my-custom-class"
                               class="regular-text">
                    </td>
                </tr>

            </table>

            <p><input type="submit" class="button button-primary" value="Save Settings"></p>

        </form>
    </div>

    <script>
        document.getElementById('algf_class_selector').addEventListener('change', function() {
            document.getElementById('algf_custom_class_row').style.display =
                (this.value === 'custom') ? '' : 'none';
        });
    </script>

    <?php
}



add_action('admin_post_algf_save_settings', function () {

    // Check nonce
    if (!check_admin_referer('algf_save_settings')) {
        wp_die('Security check failed');
    }

    // Get settings post
    $post = get_posts([
        'post_type'      => 'advanced_loop_filter',
        'posts_per_page' => 1
    ]);

    if (empty($post)) wp_die("Settings post not found");
    
    $post_id = $post[0]->ID;

    // Save fields
    if (isset($_POST['algf_heading_type'])) {
        update_post_meta($post_id, '_algf_heading_type', sanitize_text_field($_POST['algf_heading_type']));
    }

    if (isset($_POST['algf_class_selector'])) {
        update_post_meta($post_id, '_algf_class_selector', sanitize_text_field($_POST['algf_class_selector']));
    }

    if (isset($_POST['algf_custom_class']) && $_POST['algf_class_selector'] === 'custom') {
        update_post_meta($post_id, '_algf_custom_class', sanitize_text_field($_POST['algf_custom_class']));
    }

    // Redirect back to settings page
    wp_redirect(admin_url('admin.php?page=loop-grid-filter-settings&saved=1'));
    exit;
});
