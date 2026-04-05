<?php
/**
 * Silicon Plugin Install Class
 *
 * @package  silicon
 * @since    2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon_Plugin_Install' ) ) :
	/**
	 * The Silicon plugin install class
	 */
	class Silicon_Plugin_Install {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'plugin_install_scripts' ) );
			add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
		}

		/**
		 * Wrapper around the core WP get_plugins function, making sure it's actually available.
		 *
		 * @since 2.5.0
		 *
		 * @param string $plugin_folder Optional. Relative path to single plugin folder.
		 * @return array Array of installed plugins with plugin information.
		 */
		public function get_plugins( $plugin_folder = '' ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return get_plugins( $plugin_folder );
		}

		/**
		 * Helper function to extract the file path of the plugin file from the
		 * plugin slug, if the plugin is installed.
		 *
		 * @since 2.0.0
		 *
		 * @param string $slug Plugin slug (typically folder name) as provided by the developer.
		 * @return string Either file path for plugin if installed, or just the plugin slug.
		 */
		protected function get_plugin_basename_from_slug( $slug ) {
			$keys = array_keys( $this->get_plugins() );

			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}

			return $slug;
		}

		/**
		 * Check if all plugins profile are installed
		 *
		 * @param array $plugins Array of plugins and profiles.
		 * @return bool
		 */
		public function requires_install_plugins( $plugins ) {
			$requires = false;

			foreach ( $plugins as $plugin ) {
				$plugin['file_path']   = $this->get_plugin_basename_from_slug( $plugin['slug'] );
				$plugin['is_callable'] = '';

				if ( ! TGM_Plugin_Activation::is_active( $plugin ) ) {
					$requires = true;
					break;
				}
			}

			return $requires;
		}

		/**
		 * Load plugin install scripts
		 *
		 * @param string $hook_suffix the current page hook suffix.
		 * @return void
		 * @since  1.4.4
		 */
		public function plugin_install_scripts( $hook_suffix ) {
			global $silicon, $silicon_version;

			wp_enqueue_script( 'silicon-plugin-install', get_template_directory_uri() . '/assets/js/admin/plugin-install.js', array( 'jquery', 'updates' ), $silicon_version, 'all' );

			$params = [
				'tgmpa_url'   => admin_url( add_query_arg( 'page', 'tgmpa-install-plugins', 'themes.php' ) ),
				'txt_install' => esc_html__( 'Install Plugins', 'silicon' ),
				'profiles'    => $this->get_profile_params(),
			];

			if ( silicon_is_ocdi_activated() ) {
				$params['file_args'] = $silicon->ocdi->import_files();
			}
			wp_localize_script( 'silicon-plugin-install', 'ocdi_params', $params );
			wp_enqueue_script( 'silicon-plugin-install' );

			wp_enqueue_style( 'silicon-plugin-install', get_template_directory_uri() . '/assets/css/admin/plugin-install.css', array(), $silicon_version, 'all' );
		}

		/**
		 * Determines whether a plugin is active.
		 *
		 * Only plugins installed in the plugins/ folder can be active.
		 *
		 * Plugins in the mu-plugins/ folder can't be "activated," so this function will
		 * return false for those plugins.
		 *
		 * For more information on this and similar theme functions, check out
		 * the {@link https://developer.wordpress.org/themes/basics/conditional-tags/
		 * Conditional Tags} article in the Theme Developer Handbook.
		 *
		 * @param string $plugin Path to the plugin file relative to the plugins directory.
		 * @return bool True, if in the active plugins list. False, not in the list.
		 */
		public static function is_active_plugin( $plugin ) {
			return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $plugin );
		}

		/**
		 * Output a button that will install or activate a plugin if it doesn't exist, or display a disabled button if the
		 * plugin is already activated.
		 *
		 * @param string $plugin_slug The plugin slug.
		 * @param string $plugin_file The plugin file.
		 * @param string $plugin_name The plugin name.
		 * @param string $classes CSS classes.
		 * @param string $activated Button activated text.
		 * @param string $activate Button activate text.
		 * @param string $install Button install text.
		 */
		public static function install_plugin_button( $plugin_slug, $plugin_file, $plugin_name, $classes = array(), $activated = '', $activate = '', $install = '' ) {
			if ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) {
				if ( self::is_active_plugin( $plugin_slug . '/' . $plugin_file ) ) {
					// The plugin is already active.
					$button = array(
						'message' => esc_attr__( 'Activated', 'silicon' ),
						'url'     => '#',
						'classes' => array( 'silicon-button', 'disabled' ),
					);

					if ( '' !== $activated ) {
						$button['message'] = esc_attr( $activated );
					}
				} elseif ( self::is_plugin_installed( $plugin_slug ) ) {
					$url = self::is_plugin_installed( $plugin_slug );

					// The plugin exists but isn't activated yet.
					$button = array(
						'message' => esc_attr__( 'Activate', 'silicon' ),
						'url'     => $url,
						'classes' => array( 'activate-now' ),
					);

					if ( '' !== $activate ) {
						$button['message'] = esc_attr( $activate );
					}
				} else {
					// The plugin doesn't exist.
					$url    = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $plugin_slug,
							),
							self_admin_url( 'update.php' )
						),
						'install-plugin_' . $plugin_slug
					);
					$button = array(
						'message' => esc_attr__( 'Install now', 'silicon' ),
						'url'     => $url,
						'classes' => array( 'sf-install-now', 'install-now', 'install-' . $plugin_slug ),
					);

					if ( '' !== $install ) {
						$button['message'] = esc_attr( $install );
					}
				}

				if ( ! empty( $classes ) ) {
					$button['classes'] = array_merge( $button['classes'], $classes );
				}

				$button['classes'] = implode( ' ', $button['classes'] );

				?>
				<span class="plugin-card-<?php echo esc_attr( $plugin_slug ); ?>">
					<a href="<?php echo esc_url( $button['url'] ); ?>" class="<?php echo esc_attr( $button['classes'] ); ?>" data-originaltext="<?php echo esc_attr( $button['message'] ); ?>" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $plugin_slug ); ?>" aria-label="<?php echo esc_attr( $button['message'] ); ?>"><?php echo esc_html( $button['message'] ); ?></a>
				</span> <?php echo /* translators: conjunction of two alternative options user can choose (in missing plugin admin notice). */ esc_html__( 'or', 'silicon' ); ?>
				<a href="https://wordpress.org/plugins/<?php echo esc_attr( $plugin_slug ); ?>" target="_blank"><?php esc_html_e( 'learn more', 'silicon' ); ?></a>
				<?php
			}
		}

		/**
		 * Check if a plugin is installed and return the url to activate it if so.
		 *
		 * @param string $plugin_slug The plugin slug.
		 */
		private static function is_plugin_installed( $plugin_slug ) {
			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
				$plugins = get_plugins( '/' . $plugin_slug );
				if ( ! empty( $plugins ) ) {
					$keys        = array_keys( $plugins );
					$plugin_file = $plugin_slug . '/' . $keys[0];
					$url         = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'activate',
								'plugin' => $plugin_file,
							),
							admin_url( 'plugins.php' )
						),
						'activate-plugin_' . $plugin_file
					);
					return $url;
				}
			}
			return false;
		}

		/**
		 * Get profile parameters.
		 *
		 * @return array
		 */
		public function get_profile_params() {
			$profiles = $this->get_demo_profiles();
			$params   = [];
			foreach ( $profiles as $key => $profile ) {
				$plugins                            = $this->get_demo_plugins( $key );
				$params[ $key ]['requires_install'] = $this->requires_install_plugins( $plugins );
				if ( $params[ $key ]['requires_install'] ) {
					$params['all']['requires_install'] = true;
				}
			}
			return $params;
		}

		/**
		 * Get Demo Profiles
		 *
		 * @return array
		 */
		public function get_demo_profiles() {
			return array(
				'default'   => array(
					array(
						'name'        => 'Elementor',
						'slug'        => 'elementor',
						'required'    => true,
						'description' => esc_html__( 'Page Builder used to build all our Silicon Pages', 'silicon' ),
					),
					array(
						'name'        => 'Silicon Elementor',
						'slug'        => 'silicon-elementor',
						'source'      => 'https://transvelo.github.io/silicon/assets/plugins/silicon-elementor.zip',
						'required'    => true,
						'description' => esc_html__( 'Additional modules for Elementor to build our Silicon Pages', 'silicon' ),
					),
					array(
						'name'        => 'Advanced Custom Fields',
						'slug'        => 'advanced-custom-fields',
						'required'    => false,
						'description' => esc_html__( 'Additional options for Silicon Pages, Posts and Portfolio', 'silicon' ),
					),
					array(
						'name'        => 'One Click Demo Import',
						'slug'        => 'one-click-demo-import',
						'required'    => false,
						'description' => esc_html__( 'Import Silicon demo content easily with just one click.', 'silicon' ),
					),
					array(
						'name'        => 'WPForms Lite',
						'slug'        => 'wpforms-lite',
						'required'    => false,
						'description' => esc_html__( 'Use this plugin to replace HTML forms with a working form.', 'silicon' ),
					),
					array(
						'name'        => 'Safe SVG',
						'slug'        => 'safe-svg',
						'required'    => false,
						'description' => esc_html__( 'Use this plugin to upload SVG files.', 'silicon' ),
					),
				),
				'portfolio' => array(
					array(
						'name'        => 'Silicon Extensions',
						'slug'        => 'silicon-extensions',
						'source'      => 'https://transvelo.github.io/silicon/assets/plugins/silicon-extensions.zip',
						'required'    => false,
						'description' => esc_html__( 'Enable this plugin for Portfolio custom post type.', 'silicon' ),
					),
				),
				'static'    => array(
					array(
						'name'        => 'MAS Static Content',
						'slug'        => 'mas-static-content',
						'required'    => false,
						'description' => esc_html__( 'Silicon uses Static Contents for Footer and Megamenus.', 'silicon' ),
					),
				),
				'contact'   => array(
					array(
						'name'        => 'WPForms Lite',
						'slug'        => 'wpforms-lite',
						'required'    => false,
						'description' => esc_html__( 'Use this plugin to replace HTML forms with a working form.', 'silicon' ),
					),
				),
				'recommended_plugins'   => array(
					array(
						'name'               => 'HubSpot All-In-One Marketing - Forms, Popups, Live Chat',
						'slug'               => 'leadin',
						'required'           => false,
					),
				),
			);
		}

		/**
		 * Get plugins list for a given profile.
		 *
		 * @param string $demo The demo profile.
		 * @return array
		 */
		public function get_demo_plugins( $demo = 'default' ) {
			$profiles = $this->get_demo_profiles();
			$plugins  = [];

			foreach ( $profiles as $key => $profile ) {
				if ( 'all' === $demo || 'default' === $key || $key === $demo ) {
					$plugins = array_merge( $plugins, $profile );
				}
			}

			return $plugins;
		}

		/**
		 * Register the required plugins for this theme.
		 *
		 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
		 */
		public function register_required_plugins() {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */

			$profile = isset( $_GET['demo'] ) ? sanitize_text_field( wp_unslash( $_GET['demo'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$plugins = $this->get_demo_plugins( $profile );

			$config = array(
				'id'           => 'silicon', // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',        // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'has_notices'  => true,      // Show admin notices or not.
				'dismissable'  => true,      // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',        // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,     // Automatically activate plugins after installation or not.
				'message'      => '',        // Message to output right before the plugins table.
			);

			tgmpa( $plugins, $config );
		}
	}

endif;

return new Silicon_Plugin_Install();
