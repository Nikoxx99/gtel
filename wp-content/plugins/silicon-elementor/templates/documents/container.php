<?php

namespace SiliconElementor\Templates\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// phpcs:ignoreFile
class Premium_Container_Document extends Premium_Document_Base {

	public function get_name() {
		return 'container';
	}

	public static function get_title() {
		return esc_html__( 'Container', 'silicon-elementor' );
	}

	public function has_conditions() {
		return false;
	}

}