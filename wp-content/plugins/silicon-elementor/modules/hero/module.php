<?php
namespace SiliconElementor\Modules\Hero;

use SiliconElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {
	/**
	 * Get the widget of the module.
	 *
	 * @return string
	 */
	public function get_widgets() {
		return [
			'Hero',
		];
	}
	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hero';
	}
}
