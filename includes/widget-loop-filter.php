<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Elementor_Loop_Filter_Widget extends Widget_Base {

    public function get_name() {
        return 'elementor_loop_filter';
    }

    public function get_title() {
        return __( 'Loop Grid Advanced Filter', 'elementor-loop-filter' );
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
    return [ 'loop-advanced-filter' ];
    }

    public function get_keywords() {
        return [ 'filter', 'loop', 'search', 'sort', 'grid' ];
    }

    protected function render() {
        echo do_shortcode('[elementor_loop_filter]');
    }
}
