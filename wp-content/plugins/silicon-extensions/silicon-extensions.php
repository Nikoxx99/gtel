<?php
/**
 * Plugin Name:     Silicon Extensions
 * Plugin URI:      https://themeforest.net/user/madrasthemes/portfolio
 * Description:     This selection of extensions compliment our theme Silicon. Please note: they don’t work with any WordPress theme, just Silicon.
 * Author:          MadrasThemes
 * Author URI:      https://madrasthemes.com/
 * Version:         1.6.2
 * Text Domain:     Silicon
 * Domain Path:     /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Silicon_PLUGIN_FILE.
if ( ! defined( 'SILICON_PLUGIN_FILE' ) ) {
	define( 'SILICON_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'Silicon_Extensions' ) ) {
	/**
	 * Main Silicon_Extensions Class.
	 *
	 * @class Silicon_Extensions
	 * @version 1.0.0
	 * @since 1.0.0
	 * @package Silicon
	 */
	final class Silicon_Extensions {

		/**
		 * Silicon_Extensions The single instance of Silicon_Extensions.
		 *
		 * @var     object
		 * @since   1.0.0
		 */
		private static $_instance = null;

		/**
		 * The token.
		 *
		 * @var     string
		 *
		 * @since   1.0.0
		 */
		public $token;

		/**
		 * The version number.
		 *
		 * @var     string
		 *
		 * @since   1.0.0
		 */
		public $version;

		/**
		 * Constructor function.
		 *
		 * @since   1.0.0
		 * @return  void
		 */
		public function __construct() {

			$this->token   = 'silicon';
			$this->version = '1.0.0';

			add_action( 'plugins_loaded', array( $this, 'setup_constants' ), 10 );
			add_action( 'plugins_loaded', array( $this, 'includes' ), 20 );
		}

		/**
		 * Main Silicon_Extensions Instance
		 *
		 * Ensures only one instance of Silicon_Extensions is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see Silicon_Extensions()
		 * @return Main Silicon instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function setup_constants() {

			// Plugin Folder Path.
			if ( ! defined( 'SILICON_EXTENSIONS_DIR' ) ) {
				define( 'SILICON_EXTENSIONS_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'SILICON_EXTENSIONS_URL' ) ) {
				define( 'SILICON_EXTENSIONS_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'SILICON_EXTENSIONS_FILE' ) ) {
				define( 'SILICON_EXTENSIONS_FILE', __FILE__ );
			}

			// Modules File.
			if ( ! defined( 'SILICON_EXTENSIONS_MODULES_DIR' ) ) {
				define( 'SILICON_EXTENSIONS_MODULES_DIR', SILICON_EXTENSIONS_DIR . '/modules' );
			}

			$this->define( 'SILICON_ABSPATH', dirname( SILICON_EXTENSIONS_FILE ) . '/' );
			$this->define( 'SILICON_VERSION', $this->version );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or siliconend.
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'siliconend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! $this->is_rest_api_request();
			}
		}

		/**
		 * Include required files
		 *
		 * @since  1.0.0.
		 * @return void.
		 */
		public function includes() {

			/**
			 * Custom Post Types
			 */
			require SILICON_EXTENSIONS_MODULES_DIR . '/portfolio/classes/class-silicon-jetpack-portfolio.php';
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', SILICON_PLUGIN_FILE ) );
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'silicon-extensions' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'silicon-extensions' ), '1.0.0' );
		}
	}
}

/**
 * Returns the main instance of Silicon_Extensions to prevent the need to use globals.
 *
 * @since  1.0.0.
 * @return object Silicon_Extensions.
 */
function silicon_extensions() {
	return Silicon_Extensions::instance();
}

/**
 * Initialise the plugin
 */
silicon_extensions();
