<?php
namespace SiliconElementor\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use SiliconElementor\Plugin;

final class Controls_Manager {

	//const AOS_ANIMATION = 'aos_animation';
	const FONT_SIZE     = 'font_size';

	private $controls = [];

	public function __construct() {
		$this->init_actions();
	}

	public function init_actions() {
		add_action( 'elementor/controls/register', [ $this, 'init_controls' ] );
	}

	public static function get_controls_names() {
		return [
			self::FONT_SIZE
		];
	}

	public static function get_groups_names() {
		// Group name must use "-" instead of "_"
		return [];
	}

	public function init_controls() {
		foreach ( self::get_controls_names() as $control_id ) {
			$control_class_id = str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $control_id ) ) );
			$class_name = 'SiliconElementor\Includes\Controls\Control_' . $control_class_id;
			 Plugin::elementor()->controls_manager->register( new $class_name() );
		}

		foreach ( self::get_groups_names() as $group_name ) {
			$group_class_id = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $group_name ) ) );
			$class_name = 'SiliconElementor\Includes\Controls\Groups\Group_Control_' . $group_class_id;
			Plugin::elementor()->controls_manager->add_group_control( $group_name, new $class_name() );
		}
	}
}