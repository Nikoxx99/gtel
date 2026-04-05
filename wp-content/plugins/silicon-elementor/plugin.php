<?php
namespace SiliconElementor;

use Elementor\Core\Responsive\Files\Frontend as FrontendFile;
use Elementor\Core\Responsive\Responsive;
use Elementor\Utils;
use SiliconElementor\Core\Modules_Manager;
use SiliconElementor\Core\Controls_Manager;
use SiliconElementor\Core\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * Modules Manager
	 *
	 * @var Modules_Manager
	 */
	public $modules_manager;

	/**
	 * Controls Manager
	 *
	 * @var Controls_Manager
	 */
	public $controls_manager;

	/**
	 * Classes aliases
	 *
	 * @var array
	 */
	private $classes_aliases = [];

	/**
	 * Get classes aliases.
	 *
	 * @return array
	 */
	public static function get_classes_aliases() {
		if ( ! self::$classes_aliases ) {
			return self::init_classes_aliases();
		}

		return self::$classes_aliases;
	}

	/**
	 * Initialize classes aliases.
	 *
	 * @return array
	 */
	private static function init_classes_aliases() {
		$classes_aliases = [
			'SiliconElementor\Modules\PanelPostsControl\Module' => 'SiliconElementor\Modules\QueryControl\Module',
			'SiliconElementor\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'SiliconElementor\Modules\QueryControl\Controls\Group_Control_Posts',
			'SiliconElementor\Modules\PanelPostsControl\Controls\Query' => 'SiliconElementor\Modules\QueryControl\Controls\Query',
		];

		return $classes_aliases;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'silicon-elementor' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'silicon-elementor' ), '1.0.0' );
	}

	/**
	 * Elementor
	 *
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * Instance
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Autoload
	 *
	 * @param array $class Class.
	 * @return Plugin
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded.
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load    = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = SILICON_ELEMENTOR_PATH . $filename . '.php';
			$filename = str_replace( 'controls' . DIRECTORY_SEPARATOR . 'control-', 'controls' . DIRECTORY_SEPARATOR, $filename );
			$filename = str_replace( 'groups' . DIRECTORY_SEPARATOR . 'group-control-', 'groups' . DIRECTORY_SEPARATOR, $filename );

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	/**
	 * Enqueue Styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {}

	/**
	 * Enqueue Frontend Scripts.
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		$locale_settings = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'silicon-elementor-frontend' ),
			'urls'    => [
				'assets' => apply_filters( 'silicon_elementor/frontend/assets_url', SILICON_ELEMENTOR_ASSETS_URL ),
				'rest'   => get_rest_url(),
			],
		];

		/**
		 * Localize frontend settings.
		 *
		 * Filters the frontend localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		$locale_settings = apply_filters( 'silicon_elementor/frontend/localize_settings', $locale_settings );

		Utils::print_js_config(
			'silicon-elementor-frontend',
			'SiliconElementorFrontendConfig',
			$locale_settings
		);
	}

	/**
	 * Register Frontend Scripts.
	 *
	 * @return void
	 */
	public function register_frontend_scripts() {}

	/**
	 * Register Preview Scripts.
	 *
	 * @return void
	 */
	public function register_preview_scripts() {}

	/**
	 * Responsive Stylesheet Templates.
	 *
	 * @param array $templates Templates.
	 * @return array
	 */
	public function get_responsive_stylesheet_templates( $templates ) {
		return $templates;
	}

	/**
	 * Intialize Elementor.
	 *
	 * @return void
	 */
	public function on_elementor_init() {
		$this->setup_elementor();
		$this->modules_manager  = new Modules_Manager();
		$this->controls_manager = new Controls_Manager();
		$this->icons_manager    = new Icons_Manager();

		/**
		 * Silicon Elementor init.
		 *
		 * Fires on silicon Elementor init, after Elementor has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'silicon_elementor/init' );
	}

	/**
	 * Document Save Version.
	 *
	 * @param \Elementor\Core\Base\Document $document Document.
	 */
	public function on_document_save_version( $document ) {
		$document->update_meta( '_silicon_elementor_version', SILICON_ELEMENTOR_VERSION );
	}

	/**
	 * Get Responsive Templates Path.
	 *
	 * @return string
	 */
	private function get_responsive_templates_path() {
		return SILICON_ELEMENTOR_ASSETS_PATH . 'css/templates/';
	}

	/**
	 * Setup Hooks.
	 *
	 * @return void
	 */
	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

		add_action( 'elementor/document/save_version', [ $this, 'on_document_save_version' ] );
	}

	/**
	 * Setup Elementor.
	 *
	 * @return void
	 */
	public function setup_elementor() {

		if ( is_admin() && ( apply_filters( 'silicon_force_setup_elementor', false ) || get_option( 'silicon_setup_elementor' ) != 'completed' ) ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			update_option( 'elementor_optimized_dom_output', 'enabled' );
			update_option( 'elementor_unfiltered_files_upload', '1' );
			update_option( 'elementor_cpt_support', [ 'post', 'page', 'mas_static_content', 'courses', 'events' ] );

			\Elementor\Plugin::$instance->experiments->set_feature_default_state( 'e_dom_optimization', 'active' );

			// Get default/active kit.
			$active_kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();

			// Get and store current active kit settings in an array variable 'settings'.
			$kit_data['settings'] = \Elementor\Plugin::$instance->kits_manager->get_active_kit_for_frontend()->get_settings();

			if ( function_exists( 'silicon_default_colors' ) && $default_colors = silicon_default_colors() ) { //phpcs:ignore
				$kit_data['settings']['system_colors'] = $default_colors;
			}

			// Save active kit new settings.
			$active_kit->save( $kit_data );

			update_option( 'silicon_setup_elementor', 'completed' );
		}
	}

	/**
	 * Plugin constructor.
	 *
	 * @return void
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->setup_hooks();

		// Load templates file.
		require_once SILICON_ELEMENTOR_TEMPLATES_PATH . 'templates.php';
	}

	/**
	 * Get Title.
	 *
	 * @return string
	 */
	final public static function get_title() {
		return esc_html__( 'Silicon Elementor', 'silicon-elementor' );
	}
}

Plugin::instance();
