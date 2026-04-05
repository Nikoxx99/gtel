<?php
namespace SiliconElementor\Base;

use Elementor\Core\Base\Module;
use SiliconElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

abstract class Module_Base extends Module {

    public function get_widgets() {
        return [];
    }

    public function __construct() {
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );
    }

    public function init_widgets() {
        $widget_manager = Plugin::elementor()->widgets_manager;

        foreach ( $this->get_widgets() as $widget ) {
            $class_name = $this->get_reflection()->getNamespaceName() . '\Widgets\\' . $widget;

            $widget_manager->register( new $class_name() );
        }
    }

    public function add_widget_categories( $elements_manager ) {
        $elements_manager->add_category( 'silicon', [
            'title' => esc_html__( 'Silicon', 'silicon-elementor' ),
            'icon' => 'fa fa-plug',
        ] );
    }
}