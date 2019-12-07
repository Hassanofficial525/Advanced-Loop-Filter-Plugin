<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode( 'elementor_loop_filter', function() {
    ob_start(); ?>
    
    <div class="elementor-loop-filter-wrapper">
        <div class="elementor-loop-search-wrapper">
            <input type="text" id="elementor-post-search" placeholder="Search..." class="elementor-loop-search-input">
            <button type="button" id="clear-search" class="elementor-loop-clear">×</button>
        </div>

        <select id="elementor-post-sort" class="elementor-loop-sort">
            <option value="default">Sort by: Default</option>
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
            <option value="az">A - Z</option>
            <option value="za">Z - A</option>
        </select>

        <div id="elementor-no-results" class="elementor-loop-no-results">
            No matching posts found.
        </div>
    </div>
    
    <?php
    return ob_get_clean();
});
