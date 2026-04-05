<?php
namespace SiliconElementor\Modules\Breadcrumb;

use SiliconElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * The module class.
 */
class Module extends Module_Base {
	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return [
			'breadcrumb',
		];
	}
	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-breadcrumb';
	}

	/**
	 * Get if the module is active or not.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return class_exists( 'Silicon' );
	}
}
