<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'elementor/frontend/the_content', function( $content ) {
    if ( in_the_loop() && is_main_query() && strpos( $content, 'e-loop-item' ) !== false ) {
        global $post;
        if ( ! empty( $post ) && $post instanceof WP_Post ) {
            $date = get_the_date( 'Y-m-d H:i:s', $post->ID );
            $content = preg_replace(
                '/(<div[^>]*\bclass="[^"]*\be-loop-item\b[^"]*"[^>]*)(>)/',
                '$1 data-date="' . esc_attr( $date ) . '"$2',
                $content,
                1
            );
        }
    }
    return $content;
});
