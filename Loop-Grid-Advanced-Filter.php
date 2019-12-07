<?php
/**
 * Plugin Name:       Elementor Loop Filter
 * Plugin URI:        https://hassanalishoaib.com/plugins/elementor-loop-filter
 * Description:       Adds a stylish search and sort filter for Elementor Loop Grid widgets using the shortcode [elementor_loop_filter]. Fully multilingual (English/Arabic) and theme-friendly.
 * Version:           1.1.0
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

// Enqueue assets
add_action( 'wp_enqueue_scripts', function() {
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
});
