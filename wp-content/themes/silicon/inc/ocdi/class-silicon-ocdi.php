<?php
/**
 * Silicon OCDI Class
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon_OCDI' ) ) :
	/**
	 * The one click demo import class.
	 */
	class Silicon_OCDI {

		/**
		 * Stores the assets URL.
		 *
		 * @var string
		 */
		public $assets_url;

		/**
		 * Stores the demo URL.
		 *
		 * @var string
		 */
		public $demo_url;

		/**
		 * Instantiate the class.
		 */
		public function __construct() {

			$this->assets_url = 'https://transvelo.github.io/silicon/assets/';
			$this->demo_url   = 'https://silicon.madrasthemes.com/';

			add_filter( 'pt-ocdi/confirmation_dialog_options', [ $this, 'confirmation_dialog_options' ], 10, 1 );

			add_action( 'pt-ocdi/import_files', [ $this, 'import_files' ] );

			add_action( 'pt-ocdi/after_import', [ $this, 'import_wpforms' ] );
			add_action( 'pt-ocdi/enable_wp_customize_save_hooks', '__return_true' );
			add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
			add_action( 'pt-ocdi/after_import', [ $this, 'replace_uploads_dir' ] );

			add_filter( 'ocdi/register_plugins', [ $this, 'register_plugins' ] );

			add_filter( 'upload_mimes', [ $this, 'cc_mime_types' ] );
		}

		/**
		 * OCDI JSON Import
		 *
		 * @param array $mimes mimes.
		 * @return array
		 */
		public function cc_mime_types( $mimes ) {
			$mimes['json'] = 'application/json';
			$mimes['svg']  = 'image/svg+xml';
			return $mimes;
		}

		/**
		 * Register plugins in OCDI.
		 *
		 * @param array $plugins List of plugins.
		 */
		public function register_plugins( $plugins ) {
			global $silicon;

			$profile = 'default';

			if ( isset( $_GET['import'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$demo_id = absint( $_GET['import'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				switch ( $demo_id ) {
					case 10:
						$profile = 'static';
						break;
					case 11:
						$profile = 'portfolio';
						break;
					case 12:
						$profile = 'static';
						break;
					case 16:
						$profile = 'static';
						break;
					case 17:
						$profile = 'portfolio';
						break;
					case 19:
						$profile = 'static';
						break;
					case 20:
						$profile = 'contact';
						break;
				}
			}

			return $silicon->plugin_install->get_demo_plugins( $profile );
		}

		/**
		 * Confirmation dialog box options.
		 *
		 * @param array $options The dialog options.
		 * @return array
		 */
		public function confirmation_dialog_options( $options ) {
			return array_merge(
				$options,
				array(
					'width' => 410,
				)
			);
		}

		/**
		 * Trigger after import
		 */
		public function trigger_ocdi_after_import() {
			$import_files    = $this->import_files();
			$selected_import = end( $import_files );
			do_action( 'pt-ocdi/after_import', $selected_import ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}

		/**
		 * Replace uploads Dir.
		 *
		 * @param array $selected_import The import that just ran.
		 */
		public function replace_uploads_dir( $selected_import ) {
			if ( isset( $selected_import['uploads_dir'] ) ) {
				$from = $selected_import['uploads_dir'] . '/';
			} else {
				$from = 'https://silicondemos.madrasthemes.com/';
			}

			$site_upload_dir = wp_get_upload_dir();
			$to              = $site_upload_dir['baseurl'] . '/';
			\Elementor\Utils::replace_urls( $from, $to );
		}

		/**
		 * Clear default widgets.
		 */
		public function clear_default_widgets() {
			$sidebars_widgets = get_option( 'sidebars_widgets' );
			$all_widgets      = array();

			array_walk_recursive(
				$sidebars_widgets,
				function ( $item, $key ) use ( &$all_widgets ) {
					if ( ! isset( $all_widgets[ $key ] ) ) {
						$all_widgets[ $key ] = $item;
					} else {
						$all_widgets[] = $item;
					}
				}
			);

			if ( isset( $all_widgets['array_version'] ) ) {
				$array_version = $all_widgets['array_version'];
				unset( $all_widgets['array_version'] );
			}

			$new_sidebars_widgets = array_fill_keys( array_keys( $sidebars_widgets ), array() );

			$new_sidebars_widgets['wp_inactive_widgets'] = $all_widgets;
			if ( isset( $array_version ) ) {
				$new_sidebars_widgets['array_version'] = $array_version;
			}

			update_option( 'sidebars_widgets', $new_sidebars_widgets );
		}

		/**
		 * Set site options.
		 *
		 * @param array $selected_import The import that just ran.
		 */
		public function set_site_options( $selected_import ) {
			if ( isset( $selected_import['set_pages'] ) && $selected_import['set_pages'] ) {
				$front_page_title = isset( $selected_import['front_page_title'] ) ? $selected_import['front_page_title'] : 'Basic';
				$front_page_id    = get_page_by_title( $front_page_title );

				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $front_page_id->ID );
			}

			update_option( 'posts_per_page', 9 );
		}

		/**
		 * Set the nav menus.
		 *
		 * @param array $selected_import The import that just ran.
		 */
		public function set_nav_menus( $selected_import ) {
			if ( isset( $selected_import['set_nav_menus'] ) && $selected_import['set_nav_menus'] ) {
				$main_menu   = get_term_by( 'name', 'Main Menu', 'nav_menu' );
				$social_menu = get_term_by( 'name', 'Social Icons', 'nav_menu' );
				$locations   = [
					'navbar_nav'   => $main_menu->term_id,
					'social_media' => $social_menu->term_id,
				];

				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

		/**
		 * Import WPForms.
		 */
		public function import_wpforms() {

			$ocdi_host = 'https://transvelo.github.io/silicon';
			$dd_url    = $ocdi_host . '/assets/wpforms/';

			if ( ! function_exists( 'wpforms' ) || get_option( 'silicon_wpforms_imported', false ) ) {
				return;
			}

			$wpform_file_url = $dd_url . 'all.json';
			$response        = wp_remote_get( $wpform_file_url );

			if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
				return;
			}

			$form_json = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $form_json ) ) {
				return;
			}

			$forms = json_decode( $form_json, true );

			foreach ( $forms as $form_data ) {
				$form_title = $form_data['settings']['form_title'];

				if ( ! empty( $form_data['id'] ) ) {
					$form_content = array(
						'field_id' => '0',
						'settings' => array(
							'form_title' => sanitize_text_field( $form_title ),
							'form_desc'  => '',
						),
					);

					// Merge args and create the form.
					$form = array(
						'import_id'    => (int) $form_data['id'],
						'post_title'   => esc_html( $form_title ),
						'post_status'  => 'publish',
						'post_type'    => 'wpforms',
						'post_content' => wpforms_encode( $form_content ),
					);

					$form_id = wp_insert_post( $form );
				} else {
					// Create initial form to get the form ID.
					$form_id = wpforms()->form->add( $form_title );
				}

				if ( empty( $form_id ) ) {
					continue;
				}

				$form_data['id'] = $form_id;
				// Save the form data to the new form.
				wpforms()->form->update( $form_id, $form_data );
			}

			update_option( 'silicon_wpforms_imported', true );
		}

		/**
		 * Import Files from each demo.
		 */
		public function import_files() {
			$ocdi_host   = 'https://transvelo.github.io/silicon';
			$dd_url      = $ocdi_host . '/assets/xml/';
			$widget_url  = $ocdi_host . '/assets/widgets/';
			$preview_url = $ocdi_host . '/assets/img/ocdi/';
			/* translators: %1$s - The demo name. %2$s - Minutes. */
			$notice  = esc_html__( 'This demo will import %1$s. It may take %2$s', 'silicon' );
			$notice .= '<br><br>' . esc_html__( 'Menus, Widgets and Settings will not be imported. Content imported already will not be imported.', 'silicon' );
			$notice .= '<br><br>' . esc_html__( 'Alternatively, you can import this template directly into your page via Edit with Elementor.', 'silicon' );

			return apply_filters(
				'silicon_ocdi_files_args',
				array(
					array(
						'import_file_name'         => 'Template Intro Page',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'landing-intro.xml',
						'import_preview_image_url' => $preview_url . 'intro.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '25 items including 1 page & 24 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-intro/wp-content/uploads/sites/3',
					),
					array(
						'import_file_name'         => 'Mobile App Showcase',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'app-showcase.xml',
						'import_preview_image_url' => $preview_url . 'mobile-app-showcase.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '33 items including 1 page & 32 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-mobile-app-showcase/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-mobile-app-showcase/wp-content/uploads/sites/4',
					),
					array(
						'import_file_name'         => 'Mobile App Showcase v2',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'app-showcase-v2.xml',
						'import_preview_image_url' => $preview_url . 'mobile-app-showcase-v2.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '28 items including 1 page & 27 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-mobile-app-showcase-v2/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-mobile-app-showcase-v2/wp-content/uploads/sites/22',
					),
					array(
						'import_file_name'         => 'Mobile App Showcase v3',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'app-showcase-v3.xml',
						'import_preview_image_url' => $preview_url . 'mobile-app-showcase-v3.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '28 items including 1 page & 48 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-mobile-app-showcase-v3/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicon.madrasthemes.com/landing-mobile-app-showcase-v3/wp-content/uploads/sites/2',
					),
					array(
						'import_file_name'         => 'Startup',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'startup.xml',
						'import_preview_image_url' => $preview_url . 'startup.jpeg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '21 items including 1 page & 20 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/startup/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-startup/wp-content/uploads/sites/24',
					),
					array(
						'import_file_name'         => 'SaaS v1',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'saas-v1.xml',
						'import_preview_image_url' => $preview_url . 'saas-v1.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '36 items including 1 page & 35 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-saas-v1/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-saas-v1/wp-content/uploads/sites/6',
					),
					array(
						'import_file_name'         => 'SaaS v2',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'saas-v2.xml',
						'import_preview_image_url' => $preview_url . 'saas-v2.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '29 items including 1 page & 28 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-saas-v2/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-saas-v2/wp-content/uploads/sites/10',
					),
					array(
						'import_file_name'         => 'SaaS v3',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'saas-v3.xml',
						'import_preview_image_url' => $preview_url . 'saas-v3.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '39 items including 1 page & 38 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-saas-v3/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-saas-v3/wp-content/uploads/sites/21',
					),
					array(
						'import_file_name'         => 'SaaS v4',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'saas-v4.xml',
						'import_preview_image_url' => $preview_url . 'saas-v4.png',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '22 items including 1 page, 2 posts & 19 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-saas-v4/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-saas-v4/wp-content/uploads/sites/27',
					),
					array(
						'import_file_name'         => 'Financial Consulting',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'financial.xml',
						'import_preview_image_url' => $preview_url . 'financial.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '31 items including 1 page, 4 posts, 1 static-content & 25 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-financial/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-financial/wp-content/uploads/sites/11',
					),
					array(
						'import_file_name'         => 'Medical',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'medical.xml',
						'import_preview_image_url' => $preview_url . 'medical.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '42 items including 1 page, 4 posts, 1 static-content & 36 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-medical/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-medical/wp-content/uploads/sites/7',
					),
					array(
						'import_file_name'         => 'Software Company',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'software-company.xml',
						'import_preview_image_url' => $preview_url . 'software-company.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '39 items including 1 page, 5 posts, 1 static-content & 32 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-software-company/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-software-company/wp-content/uploads/sites/8',
					),
					array(
						'import_file_name'         => 'Software Dev Agency v2',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'software-dev-agency-v2.xml',
						'import_preview_image_url' => $preview_url . 'software-dev-agency-v2.png',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '24 items including 1 page & 23 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-software-dev-agency-v2/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-software-dev-agency-v2/wp-content/uploads/sites/29',
					),
					array(
						'import_file_name'         => 'Software Dev Agency v3',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'software-dev-agency-v3.xml',
						'import_preview_image_url' => $preview_url . 'software-dev-agency-v3.png',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '42 items including 1 page, 3 posts & 38 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-software-dev-agency-v3/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-software-dev-agency-v3/wp-content/uploads/sites/28',
					),
					array(
						'import_file_name'         => 'Conference',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'conference.xml',
						'import_preview_image_url' => $preview_url . 'conference.jpeg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '44 items including 1 page, 2 static-content & 41 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-conference/',
						'plugin_profile'           => 'static',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-conference/wp-content/uploads/sites/9',
					),
					array(
						'import_file_name'         => 'Digital Agency',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'digital-agency.xml',
						'import_preview_image_url' => $preview_url . 'digital-agency.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '63 items including 1 page, 6 posts, 7 projects, 1 static-content & 48 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-digital-agency/',
						'plugin_profile'           => 'portfolio',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-digital-agency/wp-content/uploads/sites/12',
					),
					array(
						'import_file_name'         => 'Blog Homepage',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'blog-homepage.xml',
						'import_preview_image_url' => $preview_url . 'blog-home.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '38 items including 1 page, 15 posts, 2 static-content & 20 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-blog',
						'plugin_profile'           => 'static',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-blog/wp-content/uploads/sites/5',
					),
					array(
						'import_file_name'         => 'Product Landing',
						'categories'               => array( 'Landings' ),
						'import_file_url'          => $dd_url . 'landing-product.xml',
						'import_preview_image_url' => $preview_url . 'landing-product.png',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '29 items including 1 page, & 28 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/landing-product',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/landing-product/wp-content/uploads/sites/26',
					),
					array(
						'import_file_name'         => 'About v1, v2 & v3',
						'categories'               => array( 'Pages' ),
						'import_file_url'          => $dd_url . 'about.xml',
						'import_preview_image_url' => $preview_url . 'about.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '76 items including 3 pages & 73 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/about-v1/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/about/wp-content/uploads/sites/14',
					),
					array(
						'import_file_name'         => 'Services',
						'categories'               => array( 'Pages' ),
						'import_file_url'          => $dd_url . 'service.xml',
						'import_preview_image_url' => $preview_url . 'services.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '66 items including 4 pages, 4 posts & 58 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/services/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/service/wp-content/uploads/sites/15',
					),
					array(
						'import_file_name'         => 'Contacts',
						'categories'               => array( 'Pages' ),
						'import_file_url'          => $dd_url . 'contacts.xml',
						'import_preview_image_url' => $preview_url . 'contact.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '4 items including 3 pages & 1 image', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/contacts-v1/',
						'plugin_profile'           => 'default',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/contacts/wp-content/uploads/sites/16',
					),
					array(
						'import_file_name'         => 'Blog',
						'categories'               => array( 'Content' ),
						'import_file_url'          => $dd_url . 'blogs.xml',
						'import_preview_image_url' => $preview_url . 'blog.jpg',
						'import_widget_file_url'   => $widget_url . 'blogs.wie',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '29 items including 5 pages, 12 posts, 1 static-content & 11 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/blog-list-with-sidebar',
						'plugin_profile'           => 'static',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/blog-pages/wp-content/uploads/sites/19',
					),
					array(
						'import_file_name'         => 'Portfolio',
						'categories'               => array( 'Content' ),
						'import_file_url'          => $dd_url . 'portfolio.xml',
						'import_preview_image_url' => $preview_url . 'portfolio.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '46 items including 3 pages, 16 projects & 27 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/portfolio-grid/',
						'plugin_profile'           => 'portfolio',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/portfolio/wp-content/uploads/sites/13',
					),
					array(
						'import_file_name'         => 'Footers',
						'categories'               => array( 'Others' ),
						'import_file_url'          => $dd_url . 'footer.xml',
						'import_preview_image_url' => $preview_url . 'footers.jpg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '16 items including 11 Static Contents & 5 images', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'plugin_profile'           => 'static',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/footer/wp-content/uploads/sites/17',
					),
					array(
						'import_file_name'         => 'Menus',
						'categories'               => array( 'Others' ),
						'import_file_url'          => $dd_url . 'menu.xml',
						'import_preview_image_url' => $preview_url . 'menus.jpg',
						'plugin_profile'           => 'static',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/menus/wp-content/uploads/sites/20',
					),
					array(
						'import_file_name'         => 'WP Forms',
						'categories'               => array( 'Others' ),
						'import_file_url'          => $dd_url . 'wpforms.xml',
						'import_preview_image_url' => $preview_url . 'wpforms.jpg',
						'plugin_profile'           => 'contact',
					),
					array(
						'import_file_name'         => 'Pricing Page',
						'categories'               => array( 'Pages' ),
						'import_file_url'          => $dd_url . 'pricing.xml',
						'import_preview_image_url' => $preview_url . 'pricing.jpeg',
						'import_notice'            => sprintf( $notice, '<strong>' . esc_html__( '7 items including 2 pages & 1 image', 'silicon' ) . '</strong>', esc_html__( 'upto a minute', 'silicon' ) ),
						'preview_url'              => 'https://silicon.madrasthemes.com/pricing/',
						'plugin_profile'           => 'static',
						'uploads_dir'              => 'https://silicondemos.madrasthemes.com/menus/wp-content/uploads/sites/23',
					),
					array(
						'import_file_name'         => 'Hubspot',
						'categories'               => array( 'CRM & Live Chat' ),
						'import_preview_image_url' => $preview_url . 'hubspot.png',
						'plugin_profile'           => 'recommended_plugins',
					),
				)
			);
		}
	}

endif;

return new Silicon_OCDI();
