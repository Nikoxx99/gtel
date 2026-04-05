<?php
/**
 * Silicon Jetpack Class
 *
 * @package  silicon
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon_Jetpack' ) ) :

	/**
	 * The Silicon Jetpack integration class
	 */
	class Silicon_Jetpack {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {}
	}

endif;

return new Silicon_Jetpack();
