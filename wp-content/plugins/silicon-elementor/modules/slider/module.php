<?php
namespace SiliconElementor\Modules\Slider;

use SiliconElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );
	}

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return [
			'Image_Comparison_Slider',
		];
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-image-comparison-slider';
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'img-comparison-slider',
			get_template_directory_uri() . '/assets/vendor/img-comparison-slider/dist/index.js',
			[],
			'5.6.6',
			true
		);
	}
}
