<?php
namespace SiliconElementor\Modules\Carousel;

use SiliconElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module for Carousel
 */
class Module extends Module_Base {

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		add_filter( 'wp_check_filetype_and_ext', [ $this, 'handle_file_type' ], 10, 3 );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );
	}

	/**
	 * Get Widgets.
	 *
	 * @return array
	 */
	public function get_widgets() {
		$widgets = array(
			'Mobile_Carousel',
			'Brand_Carousel',
			'Testimonial_Carousel',
			'Post_Carousel',
			'Iconbox_Carousel',
			'Imagebox_Carousel',
			'Content_Carousel',
			'Team_Carousel',
			'Lottie_Carousel',
			'Gallery_Carousel',
		);

		if ( class_exists( 'Silicon_Jetpack_Portfolio' ) ) {
			$widgets[] = 'Project_Carousel';
		}
		return $widgets;
	}

	/**
	 * Get Widgets Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'carousel';
	}

	/**
	 * Fixing WordPress problem when `finfo_file()` returns wrong file type
	 *
	 * @param array  $file_data The file data.
	 * @param string $file      Full path to the file.
	 * @param string $filename  The name of the file (may differ from $file due to $file being in a tmp directory).
	 *
	 * @return array
	 */
	public function handle_file_type( $file_data, $file, $filename ) {
		if ( $file_data['ext'] && $file_data['type'] ) {
			return $file_data;
		}

		$filetype = wp_check_filetype( $filename );

		if ( 'json' === $filetype['ext'] ) {
			$file_data['ext']  = 'json';
			$file_data['type'] = 'application/json';
		}

		return $file_data;
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'lottie-player',
			get_template_directory_uri() . '/assets/vendor/@lottiefiles/lottie-player/dist/lottie-player.js',
			[],
			'5.6.6',
			true
		);
	}
}
