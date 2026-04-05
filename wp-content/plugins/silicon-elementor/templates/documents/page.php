<?php

namespace SiliconElementor\Templates\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// phpcs:ignoreFile
class Premium_Page_Document extends Premium_Document_Base {

	public function get_name() {
		return 'page';
	}

	public static function get_title() {
		return __( 'Page', 'silicon-elementor' );
	}

	public function has_conditions() {
		return false;
	}

}
