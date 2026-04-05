<?php
/**
 * Silicon ACF Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon_ACF' ) ) {

	/**
	 * The Silicon ACF Integration class
	 */
	class Silicon_ACF {

		/**
		 * Setup class.
		 */
		public function __construct() {
			$this->includes();
		}

		/**
		 * Include settings.
		 */
		public function includes() {
			if ( function_exists( 'acf_add_local_field_group' ) ) {
				$settings = [ 'single-post', 'single-podcast', 'header', 'footer', 'portfolio' ];
				foreach ( $settings as $setting ) {
					require get_template_directory() . '/inc/acf/settings/' . $setting . '.php';
				}
			}
		}
	}
}

return new Silicon_ACF();
