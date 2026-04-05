<?php
/**
 * Silicon Admin Class
 *
 * @package  silicon
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon_Admin' ) ) :
	/**
	 * The Silicon admin class
	 */
	class Silicon_Admin {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {}
	}

endif;

return new Silicon_Admin();
