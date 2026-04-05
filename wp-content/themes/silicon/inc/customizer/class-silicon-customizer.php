<?php
/**
 * Silicon Customizer Class
 *
 * @package  silicon
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon_Customizer' ) ) :

	/**
	 * The Silicon Customizer class
	 */
	class Silicon_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_logos' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_header' ), 20 );
			add_action( 'customize_register', array( $this, 'customize_general' ), 15 );
			add_action( 'customize_register', array( $this, 'customize_404' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_portfolio' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_blog' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_footer' ), 30 );
			add_filter( 'body_class', array( $this, 'layout_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 130 );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_custom_control_css' ) );
			add_action( 'customize_register', array( $this, 'edit_default_customizer_settings' ), 99 );
			add_action( 'enqueue_block_assets', array( $this, 'block_editor_customizer_css' ) );
			add_action( 'init', array( $this, 'default_theme_mod_values' ), 10 );
			add_action( 'customize_register', array( $this, 'silicon_remove_customizer_sections' ), 10 );
			add_action( 'customize_register', array( $this, 'customize_customcolor' ), 10 );
		}

		/**
		 * Customize Remove Customizer sections and panels.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function silicon_remove_customizer_sections( $wp_customize ) {

			$wp_customize->remove_section( 'header_image' );
		}

		/**
		 * Customize site footer
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_footer( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_footer',
				[
					'title'       => esc_html__( 'Footer', 'silicon' ),
					'description' => esc_html__( 'Customize the theme footer.', 'silicon' ),
					'priority'    => 90,
				]
			);

			$this->add_footer_section( $wp_customize );
		}

		/**
		 * Customize all available site logos
		 *
		 * All logos located in title_tagline (Site Identity) section.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_logos( $wp_customize ) {
			$this->add_customize_logos( $wp_customize );
		}

		/**
		 * Add Customize logos.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_customize_logos( $wp_customize ) {

			// <editor-fold desc="dark_logo">
			$wp_customize->add_setting(
				'dark_logo',
				[
					'transport'         => 'postMessage',
					'theme_supports'    => 'custom-logo',
					'sanitize_callback' => 'absint',
				]
			);
			$wp_customize->add_control(
				new WP_Customize_Cropped_Image_Control(
					$wp_customize,
					'dark_logo',
					[
						'section'       => 'title_tagline',
						'label'         => esc_html__( 'Dark Logo', 'silicon' ),
						'description'   => esc_html__( 'Dark logo appears whenyou choose dark version.', 'silicon' ),
						'priority'      => 9,
						'height'        => 100, // cropper Height.
						'width'         => 100, // Cropper Width.
						'flex_width'    => true, // Flexible Width.
						'flex_height'   => true, // Flexible Height.
						'button_labels' => [
							'select'       => esc_html__( 'Select logo', 'silicon' ),
							'change'       => esc_html__( 'Change logo', 'silicon' ),
							'remove'       => esc_html__( 'Remove', 'silicon' ),
							'default'      => esc_html__( 'Default', 'silicon' ),
							'placeholder'  => esc_html__( 'No logo selected', 'silicon' ),
							'frame_title'  => esc_html__( 'Select logo', 'silicon' ),
							'frame_button' => esc_html__( 'Choose logo', 'silicon' ),
						],
					]
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'dark_logo',
				[
					'fallback_refresh' => true,
				]
			);

		}

		/**
		 * Adding Footer section controls.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_footer_section( $wp_customize ) {
			$this->static_contents = silicon_static_content_options();

			$wp_customize->add_setting(
				'footer_silicon_footer_variant',
				[
					'default'           => 'none',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'footer_silicon_footer_variant',
				[
					'type'        => 'select',
					'section'     => 'silicon_footer',
					'label'       => esc_html__( 'Footer Variant', 'silicon' ),
					'description' => esc_html__( 'This setting allows you to choose your footer variant.', 'silicon' ),
					'choices'     => array(
						'none'           => esc_html_x( 'Default', 'button', 'silicon' ),
						'static-content' => esc_html_x( 'Static Contents', 'button', 'silicon' ),
						'no-footer'      => esc_html_x( 'No Footer', 'button', 'silicon' ),
					),
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'footer_silicon_footer_variant',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'footer_static_content',
				[
					'default'           => '',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'footer_static_content',
				[
					'type'            => 'select',
					'section'         => 'silicon_footer',
					'label'           => esc_html__( 'Footer Static Content', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to choose your footer type.', 'silicon' ),
					'choices'         => $this->static_contents,
					'active_callback' => function () {
						return 'static-content' === get_theme_mod( 'footer_silicon_footer_variant', '' );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'footer_static_content',
				[
					'fallback_refresh' => true,
				]
			);

			$default_copyright_text = '© All rights reserved. Made with <i class="bx bx-heart d-inline-block fs-lg text-gradient-primary align-middle mt-n1 mx-1"></i> by&nbsp; <a href="https://madrasthemes.com/" class="text-muted" target="_blank" rel="noopener">MadrasThemes</a>';
			$wp_customize->add_setting(
				'footer_silicon_copyright_text',
				[
					'default'           => $default_copyright_text,
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => 'postMessage',
				]
			);

			$wp_customize->add_control(
				'footer_silicon_copyright_text',
				[
					'type'            => 'textarea',
					'section'         => 'silicon_footer',
					'label'           => esc_html__( 'Copyright Text', 'silicon' ),
					'active_callback' => function () {
						return 'static-content' !== get_theme_mod( 'footer_silicon_footer_variant', 'none' );
					},
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'footer_silicon_copyright_text',
				[
					'fallback_refresh' => true,
				]
			);
		}

		/**
		 * Customize site portfolio
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_portfolio( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_portfolio',
				[
					/* translators: title of section in Customizer */
					'title'       => esc_html__( 'Portfolio', 'silicon' ),
					'description' => esc_html__( 'This section contains settings related to posts listing archives and single post.', 'silicon' ),
					'priority'    => 50,
				]
			);

			$this->add_portfolio_section( $wp_customize );
		}

		/**
		 * Customize site portfolio style
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_portfolio_section( $wp_customize ) {
			$wp_customize->add_setting(
				'portfolio_layout',
				[
					'default'           => 'grid',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'portfolio_layout',
				[
					'type'        => 'select',
					'section'     => 'silicon_portfolio',
					/* translators: label field of control in Customizer */
					'label'       => esc_html__( 'Blog Layout', 'silicon' ),
					'description' => esc_html__( 'This setting affects both the posts listing (your blog page) and archives.', 'silicon' ),
					'choices'     => [
						/* translators: single item in a list of Blog Layout choices (in Customizer) */
						'grid'   => esc_html__( 'Grid', 'silicon' ),
						/* translators: single item in a list of Blog Layout choices (in Customizer) */
						'list'   => esc_html__( 'List', 'silicon' ),
						/* translators: single item in a list of Blog Layout choices (in Customizer) */
						'slider' => esc_html__( 'Slider', 'silicon' ),
					],
				]
			);
		}

		/**
		 * Customize site header
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_blog( $wp_customize ) {
			$wp_customize->add_panel(
				'silicon_blog',
				array(
					'title'       => esc_html__( 'Blog', 'silicon' ),
					'description' => esc_html__( 'Customize the theme header.', 'silicon' ),
					'priority'    => 40,

				)
			);
			$this->customize_single_podcast( $wp_customize );
			$this->customize_single_post( $wp_customize );
			$this->customize_silicon_layout( $wp_customize );
		}

		/**
		 * Customize Single Podcast
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_single_podcast( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_single_podcast',
				[
					/* translators: title of section in Customizer */
					'title'       => esc_html__( 'Single Podcast', 'silicon' ),
					'description' => esc_html__( 'This section contains settings related to posts listing archives and single post.', 'silicon' ),
					'priority'    => 50,
					'panel'       => 'silicon_blog',
				]
			);

			$this->add_single_podcast_section( $wp_customize );
		}

		/**
		 * Customize Single Post
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_single_podcast_section( $wp_customize ) {
			$this->static_contents = silicon_static_content_options();
			$wp_customize->add_setting(
				'single_podcast_layout',
				[
					'default'           => '',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'single_podcast_layout',
				[
					'type'    => 'select',
					'section' => 'silicon_single_podcast',
					/* translators: label field of control in Customizer */
					'label'   => esc_html__( 'Static Content', 'silicon' ),
					'choices' => $this->static_contents,
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_podcast_layout',
				[
					'fallback_refresh' => true,
				]
			);
		}

		/**
		 * Customize Single post
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_single_post( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_single_post',
				[
					/* translators: title of section in Customizer */
					'title'    => esc_html__( 'Single Post', 'silicon' ),
					'priority' => 50,
					'panel'    => 'silicon_blog',
				]
			);

			$this->add_single_post_section( $wp_customize );
		}

		/**
		 * Customize Single Post
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_single_post_section( $wp_customize ) {
			$this->static_contents = silicon_static_content_options();
			$wp_customize->add_setting(
				'single_post_layout',
				[
					'default'           => '',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'single_post_layout',
				[
					'type'        => 'select',
					'section'     => 'silicon_single_post',
					/* translators: label field of control in Customizer */
					'label'       => esc_html__( 'Static Content', 'silicon' ),
					'description' => esc_html__( 'This setting affects both the posts listing (your blog page) and archives.', 'silicon' ),
					'choices'     => $this->static_contents,
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'single_post_layout',
				[
					'fallback_refresh' => true,
				]
			);
		}

		/**
		 * Customize Single post
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_silicon_layout( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_blog_layout',
				[
					/* translators: title of section in Customizer */
					'title'    => esc_html__( 'Layout', 'silicon' ),
					'priority' => 50,
					'panel'    => 'silicon_blog',
				]
			);

			$this->add_silicon_sidebar_layout( $wp_customize );
			$this->add_silicon_blog_layout_view_style( $wp_customize );
		}

		/**
		 * Customize Sidebar
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_silicon_sidebar_layout( $wp_customize ) {
			$wp_customize->add_setting(
				'blog_sidebar',
				array(
					'default'           => 'sidebar-right',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'blog_sidebar',
				array(
					'type'     => 'select',
					'section'  => 'silicon_blog_layout',
					'priority' => 50,
					/* translators: label field of control in Customizer */
					'label'    => esc_html__( 'Blog Sidebar', 'silicon' ),
					'choices'  => array(
						'sidebar-left'  => esc_html__( 'Left Sidebar', 'silicon' ),
						'sidebar-right' => esc_html__( 'Right Sidebar', 'silicon' ),
						''              => esc_html__( 'Full Width', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blog_sidebar',
				array(
					'fallback_refresh' => true,
				)
			);
		}

		/**
		 * Customize blog view style
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_silicon_blog_layout_view_style( $wp_customize ) {
			$wp_customize->add_setting(
				'silicon_blog_layout_style',
				[
					'default'           => 'default',
					'sanitize_callback' => 'sanitize_key',
				]
			);
			$wp_customize->add_control(
				'silicon_blog_layout_style',
				[
					'type'        => 'select',
					'section'     => 'silicon_blog_layout',
					/* translators: label field of control in Customizer */
					'label'       => esc_html__( 'Blog Layout', 'silicon' ),
					'description' => esc_html__( 'This setting affects both the posts listing (your blog page) and archives.', 'silicon' ),
					'choices'     => [
						/* translators: single item in a list of Blog Layout choices (in Customizer) */
						'default' => esc_html__( 'Grid', 'silicon' ),
						'grid-v2' => esc_html__( 'Grid v2', 'silicon' ),
						/* translators: single item in a list of Blog Layout choices (in Customizer) */
						'list'    => esc_html__( 'List', 'silicon' ),
					],
				]
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_blog_layout_style',
				array(
					'fallback_refresh' => true,
				)
			);
		}

		/**
		 * Customize site header
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_general( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_general',
				[
					'title'       => esc_html__( 'General', 'silicon' ),
					'description' => esc_html__( 'Customize general options.', 'silicon' ),
					'priority'    => 20,
				]
			);

			$this->add_general_section( $wp_customize );

		}

		/**
		 * Customizer Controls For Header.
		 *
		 *  @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_general_section( $wp_customize ) {

			$wp_customize->add_setting(
				'enable_silicon_page_loader',
				array(
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'enable_silicon_page_loader',
				array(
					'type'    => 'radio',
					'section' => 'silicon_general',
					'label'   => esc_html__( 'Page Loader', 'silicon' ),
					'choices' => array(
						'yes' => esc_html__( 'Enable', 'silicon' ),
						'no'  => esc_html__( 'Disable', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_silicon_page_loader',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_enable_system_mode',
				array(
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_enable_system_mode',
				array(
					'type'        => 'radio',
					'section'     => 'silicon_general',
					'label'       => esc_html__( 'Enable System Mode Switch', 'silicon' ),
					'description' => esc_html__( 'Switches theme mode regarding system appearance', 'silicon' ),
					'choices'     => array(
						'yes' => esc_html__( 'Enable', 'silicon' ),
						'no'  => esc_html__( 'Disable', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_enable_system_mode',
				[
					'fallback_refresh' => true,
				]
			);

		}

		/**
		 * Customize site header
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_header( $wp_customize ) {
			$wp_customize->add_section(
				'silicon_header',
				[
					'title'       => esc_html__( 'Header', 'silicon' ),
					'description' => esc_html__( 'Customize the theme header.', 'silicon' ),
					'priority'    => 20,
				]
			);

			$this->add_header_section( $wp_customize );

		}

		/**
		 * Customizer Controls For Header.
		 *
		 *  @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_header_section( $wp_customize ) {

			$wp_customize->add_setting(
				'enable_silicon_mode_switcher',
				array(
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'enable_silicon_mode_switcher',
				array(
					'type'    => 'radio',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Enable Mode Switcher?', 'silicon' ),
					'choices' => array(
						'yes' => esc_html__( 'Yes', 'silicon' ),
						'no'  => esc_html__( 'No', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_silicon_mode_switcher',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_default_mode',
				array(
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_default_mode',
				array(
					'type'    => 'radio',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Default Mode', 'silicon' ),
					'choices' => array(
						'no'  => esc_html__( 'Light', 'silicon' ),
						'yes' => esc_html__( 'Dark', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_default_mode',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_select_header_navbar_position',
				array(
					'default'           => 'default',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_select_header_navbar_position',
				array(
					'type'    => 'select',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Navbar Position', 'silicon' ),
					'choices' => array(
						'default'           => esc_html_x( 'Default', 'button', 'silicon' ),
						'fixed-top'         => esc_html_x( 'Fixed Top', 'button', 'silicon' ),
						'position-absolute' => esc_html_x( 'Absolute', 'button', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_select_header_navbar_position',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_silicon_header_sticky',
				array(
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'enable_silicon_header_sticky',
				array(
					'type'    => 'radio',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Stick Navbar on Scroll', 'silicon' ),
					'choices' => array(
						'yes' => esc_html__( 'Yes', 'silicon' ),
						'no'  => esc_html__( 'No', 'silicon' ),
					),
					'active_callback' => function () {
						return 'fixed-top' !== get_theme_mod( 'silicon_select_header_navbar_position', 'default' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_silicon_header_sticky',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_header_navbar_text',
				array(
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_header_navbar_text',
				array(
					'type'    => 'radio',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Navbar Text', 'silicon' ),
					'choices' => array(
						'light' => esc_html__( 'Light', 'silicon' ),
						'dark'  => esc_html__( 'Dark', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_header_navbar_text',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_select_header_background',
				array(
					'default'           => 'default',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_select_header_background',
				array(
					'type'    => 'select',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Background', 'silicon' ),
					'choices' => array(
						'default'  => esc_html_x( 'Transparent', 'button', 'silicon' ),
						'bg-light' => esc_html_x( 'Light', 'button', 'silicon' ),
						'bg-dark'  => esc_html_x( 'Dark', 'button', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_select_header_background',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_silicon_header_shadow',
				array(
					'default'           => 'yes',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'enable_silicon_header_shadow',
				array(
					'type'    => 'radio',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Enable Shadow ?', 'silicon' ),
					'choices' => array(
						'yes' => esc_html__( 'Yes', 'silicon' ),
						'no'  => esc_html__( 'No', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_silicon_header_shadow',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'disable_silicon_header_dark_shadow',
				array(
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'disable_silicon_header_dark_shadow',
				array(
					'type'            => 'radio',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Disable Dark Mode Shadow ?', 'silicon' ),
					'choices'         => array(
						'yes' => esc_html__( 'Yes', 'silicon' ),
						'no'  => esc_html__( 'No', 'silicon' ),
					),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_silicon_header_shadow', 'yes' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'disable_silicon_header_dark_shadow',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_header_border',
				array(
					'default'           => 'none',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_header_border',
				array(
					'type'    => 'select',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Border', 'silicon' ),
					'choices' => array(
						'none'   => esc_html_x( 'None', 'border', 'silicon' ),
						'top'    => esc_html_x( 'Top', 'border', 'silicon' ),
						'bottom' => esc_html_x( 'Bottom', 'border', 'silicon' ),
						'start'  => esc_html_x( 'Start', 'border', 'silicon' ),
						'end'    => esc_html_x( 'End', 'border', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_header_border',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_header_border_color',
				array(
					'default'           => 'light',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_header_border_color',
				array(
					'type'            => 'select',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Header Border Color', 'silicon' ),
					'choices'         => array(
						'primary'   => esc_html_x( 'Primary', 'border', 'silicon' ),
						'secondary' => esc_html_x( 'Secondary', 'border', 'silicon' ),
						'success'   => esc_html_x( 'Success', 'border', 'silicon' ),
						'danger'    => esc_html_x( 'Danger', 'border', 'silicon' ),
						'warning'   => esc_html_x( 'Warning', 'border', 'silicon' ),
						'info'      => esc_html_x( 'Info', 'border', 'silicon' ),
						'dark'      => esc_html_x( 'Dark', 'border', 'silicon' ),
						'light'     => esc_html_x( 'Light', 'border', 'silicon' ),
						'link'      => esc_html_x( 'Link', 'border', 'silicon' ),
					),
					'active_callback' => function () {
						return 'none' !== get_theme_mod( 'silicon_header_border', 'none' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_header_border_color',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'enable_primary_nav_button',
				array(
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'enable_primary_nav_button',
				array(
					'type'    => 'radio',
					'section' => 'silicon_header',
					'label'   => esc_html__( 'Enable Buy Now Button ?', 'silicon' ),
					'choices' => array(
						'yes' => esc_html__( 'Yes', 'silicon' ),
						'no'  => esc_html__( 'No', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'enable_primary_nav_button',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_button_icon',
				array(
					'default'           => 'bx bx-cart',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				'silicon_button_icon',
				array(
					'type'            => 'text',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Change Buy Now Button Icon', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to change the button icon', 'silicon' ),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'no' );
					},

				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_button_icon',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'header_button_color',
				array(
					'default'           => 'primary',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'header_button_color',
				array(
					'type'            => 'select',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Now Button Color', 'silicon' ),
					'choices'         => array(
						'primary'   => esc_html_x( 'Primary', 'button', 'silicon' ),
						'secondary' => esc_html_x( 'Secondary', 'button', 'silicon' ),
						'success'   => esc_html_x( 'Success', 'button', 'silicon' ),
						'danger'    => esc_html_x( 'Danger', 'button', 'silicon' ),
						'warning'   => esc_html_x( 'Warning', 'button', 'silicon' ),
						'info'      => esc_html_x( 'Info', 'button', 'silicon' ),
						'dark'      => esc_html_x( 'Dark', 'button', 'silicon' ),
						'light'     => esc_html_x( 'Light', 'button', 'silicon' ),
						'link'      => esc_html_x( 'Link', 'button', 'silicon' ),
					),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'yes' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'header_button_color',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'header_button_text',
				array(
					'default'           => esc_html__( 'Buy now', 'silicon' ),
					'sanitize_callback' => 'esc_html',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'header_button_text',
				array(
					'type'            => 'text',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Now Button Text', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to change the button text', 'silicon' ),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'yes' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'header_button_text',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'header_button_url',
				array(
					'default'           => '#',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'header_button_url',
				array(
					'type'            => 'url',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Now Button Link', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to change the button link', 'silicon' ),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'yes' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'header_button_url',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_header_button_size',
				array(
					'default'           => 'sm',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_header_button_size',
				array(
					'type'            => 'select',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Now Button Size', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to choose your header button size.', 'silicon' ),
					'choices'         => array(
						'sm' => esc_html__( 'Small', 'silicon' ),
						''   => esc_html__( 'Normal', 'silicon' ),
						'lg' => esc_html__( 'Large', 'silicon' ),
					),
					'active_callback' => function () {
							return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'yes' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_header_button_size',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_header_button_shape',
				array(
					'default'           => 'rounded',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'silicon_header_button_shape',
				array(
					'type'            => 'select',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Now Button Shape', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to choose your header button shape.', 'silicon' ),
					'choices'         => array(
						'rounded'      => esc_html__( 'Default', 'silicon' ),
						'rounded-pill' => esc_html__( 'Pill', 'silicon' ),
						'rounded-0'    => esc_html__( 'Square', 'silicon' ),
					),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'yes' );
					},

				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_header_button_shape',
				[
					'fallback_refresh' => true,
				]
			);

			$wp_customize->add_setting(
				'silicon_header_button_css',
				array(
					'default'           => 'fs-sm',
					'sanitize_callback' => 'sanitize_key',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'silicon_header_button_css',
				array(
					'type'            => 'text',
					'section'         => 'silicon_header',
					'label'           => esc_html__( 'Buy Now Button CSS', 'silicon' ),
					'description'     => esc_html__( 'This setting allows you to add button css classes', 'silicon' ),
					'active_callback' => function () {
						return 'yes' === get_theme_mod( 'enable_primary_nav_button', 'yes' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'silicon_header_button_css',
				[
					'fallback_refresh' => true,
				]
			);
		}


		/**
		 * Customize 404 Page
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_404( $wp_customize ) {

			$wp_customize->add_section(
				'silicon_404',
				array(
					'title'    => esc_html__( '404', 'silicon' ),
					'priority' => 30,
				)
			);

			$this->add_404_section( $wp_customize );
		}

		/**
		 * Customize 404
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		private function add_404_section( $wp_customize ) {

			$wp_customize->add_setting(
				'404_version',
				array(
					'default'           => 'v1',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'404_version',
				array(
					'type'        => 'select',
					'section'     => 'silicon_404',
					'label'       => esc_html__( '404 Page Variant', 'silicon' ),
					'description' => esc_html__( 'This setting allows you to choose your 404 page types.', 'silicon' ),
					'choices'     => array(
						'v1' => esc_html__( '404 v1', 'silicon' ),
						'v2' => esc_html__( '404 v2', 'silicon' ),
					),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'404_image_option',
				array(
					'selector'        => '.404_image',
					'render_callback' => function () {
						return esc_html( get_theme_mod( '404_image_option' ) );
					},
				)
			);

			$wp_customize->add_setting(
				'404_image_option',
				array(
					'transport'         => 'postMessage',
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					'404_image_option',
					array(
						'section'     => 'silicon_404',
						'label'       => esc_html__( '404 Image Upload', 'silicon' ),
						'description' => esc_html__(
							'This setting allows you to upload an image for 404 page.',
							'silicon'
						),
						'mime_type'   => 'image',
					)
				)
			);

			$wp_customize->add_setting(
				'404_title',
				array(
					'default'           => esc_html_x( 'Error 404', 'front-end', 'silicon' ),
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'404_title',
				array(
					'type'    => 'text',
					'section' => 'silicon_404',
					'label'   => esc_html__( '404 Title', 'silicon' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'404_title',
				array(
					'render_callback' => function () {
						return esc_html( get_theme_mod( '404_title' ) );
					},
				)
			);

			$wp_customize->add_setting(
				'404_description',
				array(
					'default'           => esc_html_x( 'The page you are looking for was moved, removed or might never existed.', 'front-end', 'silicon' ),
					'sanitize_callback' => 'sanitize_textarea_field',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'404_description',
				array(
					'type'    => 'textarea',
					'section' => 'silicon_404',
					'label'   => esc_html__( 'Description', 'silicon' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'404_description',
				array(
					'render_callback' => function () {
						return esc_html( get_theme_mod( '404_subtitle' ) );
					},
				)
			);

			$wp_customize->add_setting(
				'404_button_text',
				array(
					'default'           => esc_html__( 'Go to home page', 'silicon' ),
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'404_button_text',
				array(
					'type'        => 'text',
					'section'     => 'silicon_404',
					'label'       => esc_html__( 'Action button text', 'silicon' ),
					'description' => esc_html__( 'This setting allows you to change the button text', 'silicon' ),

				)
			);

			$wp_customize->selective_refresh->add_partial(
				'404_button_text',
				array(
					'fallback_refresh' => true,
				)
			);

			$wp_customize->add_setting(
				'404_button_color',
				array(
					'default'           => 'primary',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'404_button_color',
				array(
					'type'    => 'select',
					'section' => 'silicon_404',
					'label'   => esc_html__( 'Action button color', 'silicon' ),
					'choices' => array(
						'primary'  => esc_html_x( 'Primary', 'button', 'silicon' ),
						'success'  => esc_html_x( 'Success', 'button', 'silicon' ),
						'danger'   => esc_html_x( 'Danger', 'button', 'silicon' ),
						'warning'  => esc_html_x( 'Warning', 'button', 'silicon' ),
						'info'     => esc_html_x( 'Info', 'button', 'silicon' ),
						'dark'     => esc_html_x( 'Dark', 'button', 'silicon' ),
						'gradient' => esc_html_x( 'Gradient', 'button', 'silicon' ),
						'link'     => esc_html_x( 'Link', 'button', 'silicon' ),
					),
				)
			);

			$wp_customize->add_setting(
				'404_button_url',
				array(
					'default'           => '#',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'404_button_url',
				array(
					'type'        => 'url',
					'section'     => 'silicon_404',
					'label'       => esc_html__( 'Button Url', 'silicon' ),
					'description' => esc_html__( 'This setting allows you to change button url.', 'silicon' ),
				)
			);

			$wp_customize->add_setting(
				'404_button_size',
				array(
					'default'           => 'lg',
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$wp_customize->add_control(
				'404_button_size',
				array(
					'type'        => 'select',
					'section'     => 'silicon_404',
					'label'       => esc_html__( 'Primary Button Size', 'silicon' ),
					'description' => esc_html__( 'This setting allows you to choose your 404 page primary button size.', 'silicon' ),
					'choices'     => array(
						'sm' => esc_html__( 'Small', 'silicon' ),
						'md' => esc_html__( 'Medium', 'silicon' ),
						'lg' => esc_html__( 'Large', 'silicon' ),
					),

				)
			);
		}


		/**
		 * Returns an array of the desired default Silicon Options
		 *
		 * @return array
		 */
		public function get_silicon_default_setting_values() {
			return apply_filters(
				'silicon_setting_default_values',
				$args = array(
					'silicon_heading_color'               => '#333333',
					'silicon_text_color'                  => '#6d6d6d',
					'silicon_accent_color'                => '#7f54b3',
					'silicon_hero_heading_color'          => '#000000',
					'silicon_hero_text_color'             => '#000000',
					'silicon_button_background_color'     => '#eeeeee',
					'silicon_button_text_color'           => '#333333',
					'silicon_button_alt_background_color' => '#333333',
					'silicon_button_alt_text_color'       => '#ffffff',
					'background_color'                    => 'ffffff',
				)
			);
		}

		/**
		 * Adds a value to each Silicon setting if one isn't already present.
		 *
		 * @uses get_silicon_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( $this->get_silicon_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value Theme modification value.
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = $this->get_silicon_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter silicon_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_silicon_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( $this->get_silicon_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {

			// Move background color setting alongside background image.
			$wp_customize->get_control( 'background_color' )->section  = 'background_image';
			$wp_customize->get_control( 'background_color' )->priority = 20;

			// Change background image section title & priority.
			$wp_customize->get_section( 'background_image' )->title    = esc_html__( 'Background', 'silicon' );
			$wp_customize->get_section( 'background_image' )->priority = 30;

			// Selective refresh.
			if ( function_exists( 'add_partial' ) ) {
				$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
				$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

				$wp_customize->selective_refresh->add_partial(
					'custom_logo',
					array(
						'selector'        => '.site-branding',
						'render_callback' => array( $this, 'get_site_logo' ),
					)
				);

				$wp_customize->selective_refresh->add_partial(
					'blogname',
					array(
						'selector'        => '.site-title.beta a',
						'render_callback' => array( $this, 'get_site_name' ),
					)
				);

				$wp_customize->selective_refresh->add_partial(
					'blogdescription',
					array(
						'selector'        => '.site-description',
						'render_callback' => array( $this, 'get_site_description' ),
					)
				);
			}

			/**
			 * Custom controls
			 */
			require_once dirname( __FILE__ ) . '/class-silicon-customizer-control-radio-image.php';
			require_once dirname( __FILE__ ) . '/class-silicon-customizer-control-arbitrary.php';

			/**
			 * Add the typography section
			 */
			$wp_customize->add_section(
				'silicon_typography',
				array(
					'title'    => esc_html__( 'Typography', 'silicon' ),
					'priority' => 45,
				)
			);

			/**
			 * Heading color
			 */
			$wp_customize->add_setting(
				'silicon_heading_color',
				array(
					'default'           => apply_filters( 'silicon_default_heading_color', '#484c51' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_heading_color',
					array(
						'label'    => esc_html__( 'Heading color', 'silicon' ),
						'section'  => 'silicon_typography',
						'settings' => 'silicon_heading_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Text Color
			 */
			$wp_customize->add_setting(
				'silicon_text_color',
				array(
					'default'           => apply_filters( 'silicon_default_text_color', '#43454b' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_text_color',
					array(
						'label'    => esc_html__( 'Text color', 'silicon' ),
						'section'  => 'silicon_typography',
						'settings' => 'silicon_text_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Accent Color
			 */
			$wp_customize->add_setting(
				'silicon_accent_color',
				array(
					'default'           => apply_filters( 'silicon_default_accent_color', '#7f54b3' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_accent_color',
					array(
						'label'    => esc_html__( 'Link / accent color', 'silicon' ),
						'section'  => 'silicon_typography',
						'settings' => 'silicon_accent_color',
						'priority' => 40,
					)
				)
			);

			/**
			 * Hero Heading Color
			 */
			$wp_customize->add_setting(
				'silicon_hero_heading_color',
				array(
					'default'           => apply_filters( 'silicon_default_hero_heading_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_hero_heading_color',
					array(
						'label'    => esc_html__( 'Hero heading color', 'silicon' ),
						'section'  => 'silicon_typography',
						'settings' => 'silicon_hero_heading_color',
						'priority' => 50,
					)
				)
			);

			/**
			 * Hero Text Color
			 */
			$wp_customize->add_setting(
				'silicon_hero_text_color',
				array(
					'default'           => apply_filters( 'silicon_default_hero_text_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_hero_text_color',
					array(
						'label'    => esc_html__( 'Hero text color', 'silicon' ),
						'section'  => 'silicon_typography',
						'settings' => 'silicon_hero_text_color',
						'priority' => 60,
					)
				)
			);

			/**
			 * Buttons section
			 */
			$wp_customize->add_section(
				'silicon_buttons',
				array(
					'title'       => esc_html__( 'Buttons', 'silicon' ),
					'priority'    => 45,
					'description' => esc_html__( 'Customize the look & feel of your website buttons.', 'silicon' ),
				)
			);

			/**
			 * Button background color
			 */
			$wp_customize->add_setting(
				'silicon_button_background_color',
				array(
					'default'           => apply_filters( 'silicon_default_button_background_color', '#96588a' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_button_background_color',
					array(
						'label'    => esc_html__( 'Background color', 'silicon' ),
						'section'  => 'silicon_buttons',
						'settings' => 'silicon_button_background_color',
						'priority' => 10,
					)
				)
			);

			/**
			 * Button text color
			 */
			$wp_customize->add_setting(
				'silicon_button_text_color',
				array(
					'default'           => apply_filters( 'silicon_default_button_text_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_button_text_color',
					array(
						'label'    => esc_html__( 'Text color', 'silicon' ),
						'section'  => 'silicon_buttons',
						'settings' => 'silicon_button_text_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Button alt background color
			 */
			$wp_customize->add_setting(
				'silicon_button_alt_background_color',
				array(
					'default'           => apply_filters( 'silicon_default_button_alt_background_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_button_alt_background_color',
					array(
						'label'    => esc_html__( 'Alternate button background color', 'silicon' ),
						'section'  => 'silicon_buttons',
						'settings' => 'silicon_button_alt_background_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Button alt text color
			 */
			$wp_customize->add_setting(
				'silicon_button_alt_text_color',
				array(
					'default'           => apply_filters( 'silicon_default_button_alt_text_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_button_alt_text_color',
					array(
						'label'    => esc_html__( 'Alternate button text color', 'silicon' ),
						'section'  => 'silicon_buttons',
						'settings' => 'silicon_button_alt_text_color',
						'priority' => 40,
					)
				)
			);
		}

		/**
		 * Get all of the Silicon theme mods.
		 *
		 * @return array $silicon_theme_mods The Silicon Theme Mods.
		 */
		public function get_silicon_theme_mods() {
			$silicon_theme_mods = array(
				'background_color'            => silicon_get_content_background_color(),
				'accent_color'                => get_theme_mod( 'silicon_accent_color' ),
				'hero_heading_color'          => get_theme_mod( 'silicon_hero_heading_color' ),
				'hero_text_color'             => get_theme_mod( 'silicon_hero_text_color' ),
				'text_color'                  => get_theme_mod( 'silicon_text_color' ),
				'heading_color'               => get_theme_mod( 'silicon_heading_color' ),
				'button_background_color'     => get_theme_mod( 'silicon_button_background_color' ),
				'button_text_color'           => get_theme_mod( 'silicon_button_text_color' ),
				'button_alt_background_color' => get_theme_mod( 'silicon_button_alt_background_color' ),
				'button_alt_text_color'       => get_theme_mod( 'silicon_button_alt_text_color' ),
			);

			return apply_filters( 'silicon_theme_mods', $silicon_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_silicon_theme_mods()
		 * @return array $styles the css
		 */
		public function get_css() {
			$silicon_theme_mods = $this->get_silicon_theme_mods();
			$brighten_factor    = apply_filters( 'silicon_brighten_factor', 25 );
			$darken_factor      = apply_filters( 'silicon_darken_factor', -25 );

			$styles = '';

			return apply_filters( 'silicon_customizer_css', $styles );
		}

		/**
		 * Get Gutenberg Customizer css.
		 *
		 * @see get_silicon_theme_mods()
		 * @return array $styles the css
		 */
		public function gutenberg_get_css() {
			$silicon_theme_mods = $this->get_silicon_theme_mods();
			$darken_factor      = apply_filters( 'silicon_darken_factor', -25 );

			// Gutenberg.
			$styles = '
				.wp-block-button__link:not(.has-text-color) {
					color: ' . $silicon_theme_mods['button_text_color'] . ';
				}

				.wp-block-button__link:not(.has-text-color):hover,
				.wp-block-button__link:not(.has-text-color):focus,
				.wp-block-button__link:not(.has-text-color):active {
					color: ' . $silicon_theme_mods['button_text_color'] . ';
				}

				.wp-block-button__link:not(.has-background) {
					background-color: ' . $silicon_theme_mods['button_background_color'] . ';
				}

				.wp-block-button__link:not(.has-background):hover,
				.wp-block-button__link:not(.has-background):focus,
				.wp-block-button__link:not(.has-background):active {
					border-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['button_background_color'], $darken_factor ) . ';
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['button_background_color'], $darken_factor ) . ';
				}

				.wp-block-quote footer,
				.wp-block-quote cite,
				.wp-block-quote__citation {
					color: ' . $silicon_theme_mods['text_color'] . ';
				}

				.wp-block-pullquote cite,
				.wp-block-pullquote footer,
				.wp-block-pullquote__citation {
					color: ' . $silicon_theme_mods['text_color'] . ';
				}

				.wp-block-image figcaption {
					color: ' . $silicon_theme_mods['text_color'] . ';
				}

				.wp-block-separator.is-style-dots::before {
					color: ' . $silicon_theme_mods['heading_color'] . ';
				}

				.wp-block-file a.wp-block-file__button {
					color: ' . $silicon_theme_mods['button_text_color'] . ';
					background-color: ' . $silicon_theme_mods['button_background_color'] . ';
					border-color: ' . $silicon_theme_mods['button_background_color'] . ';
				}

				.wp-block-file a.wp-block-file__button:hover,
				.wp-block-file a.wp-block-file__button:focus,
				.wp-block-file a.wp-block-file__button:active {
					color: ' . $silicon_theme_mods['button_text_color'] . ';
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['button_background_color'], $darken_factor ) . ';
				}

				.wp-block-code,
				.wp-block-preformatted pre {
					color: ' . $silicon_theme_mods['text_color'] . ';
				}

				.wp-block-table:not( .has-background ):not( .is-style-stripes ) tbody tr:nth-child(2n) td {
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['background_color'], -2 ) . ';
				}

				.wp-block-cover .wp-block-cover__inner-container h1:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h2:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h3:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h4:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h5:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h6:not(.has-text-color) {
					color: ' . $silicon_theme_mods['hero_heading_color'] . ';
				}

				.wc-block-components-price-slider__range-input-progress,
				.rtl .wc-block-components-price-slider__range-input-progress {
					--range-color: ' . $silicon_theme_mods['accent_color'] . ';
				}

				/* Target only IE11 */
				@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
					.wc-block-components-price-slider__range-input-progress {
						background: ' . $silicon_theme_mods['accent_color'] . ';
					}
				}

				.wc-block-components-button:not(.is-link) {
					background-color: ' . $silicon_theme_mods['button_alt_background_color'] . ';
					color: ' . $silicon_theme_mods['button_alt_text_color'] . ';
				}

				.wc-block-components-button:not(.is-link):hover,
				.wc-block-components-button:not(.is-link):focus,
				.wc-block-components-button:not(.is-link):active {
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['button_alt_background_color'], $darken_factor ) . ';
					color: ' . $silicon_theme_mods['button_alt_text_color'] . ';
				}

				.wc-block-components-button:not(.is-link):disabled {
					background-color: ' . $silicon_theme_mods['button_alt_background_color'] . ';
					color: ' . $silicon_theme_mods['button_alt_text_color'] . ';
				}

				.wc-block-cart__submit-container {
					background-color: ' . $silicon_theme_mods['background_color'] . ';
				}

				.wc-block-cart__submit-container::before {
					color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['background_color'], is_color_light( $silicon_theme_mods['background_color'] ) ? -35 : 70, 0.5 ) . ';
				}

				.wc-block-components-order-summary-item__quantity {
					background-color: ' . $silicon_theme_mods['background_color'] . ';
					border-color: ' . $silicon_theme_mods['text_color'] . ';
					box-shadow: 0 0 0 2px ' . $silicon_theme_mods['background_color'] . ';
					color: ' . $silicon_theme_mods['text_color'] . ';
				}
			';

			return apply_filters( 'silicon_gutenberg_customizer_css', $styles );
		}

		/**
		 * Enqueue dynamic colors to use editor blocks.
		 *
		 * @since 2.4.0
		 */
		public function block_editor_customizer_css() {
			$silicon_theme_mods = $this->get_silicon_theme_mods();

			$styles = '';

			if ( is_admin() ) {
				$styles .= '
				.editor-styles-wrapper {
					background-color: ' . $silicon_theme_mods['background_color'] . ';
				}

				.editor-styles-wrapper table:not( .has-background ) th {
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['background_color'], -7 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody td {
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['background_color'], -2 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody tr:nth-child(2n) td,
				.editor-styles-wrapper fieldset,
				.editor-styles-wrapper fieldset legend {
					background-color: ' . silicon_adjust_color_brightness( $silicon_theme_mods['background_color'], -4 ) . ';
				}

				.editor-post-title__block .editor-post-title__input,
				.editor-styles-wrapper h1,
				.editor-styles-wrapper h2,
				.editor-styles-wrapper h3,
				.editor-styles-wrapper h4,
				.editor-styles-wrapper h5,
				.editor-styles-wrapper h6 {
					color: ' . $silicon_theme_mods['heading_color'] . ';
				}

				/* WP <=5.3 */
				.editor-styles-wrapper .editor-block-list__block,
				/* WP >=5.4 */
				.editor-styles-wrapper .block-editor-block-list__block {
					color: ' . $silicon_theme_mods['text_color'] . ';
				}

				.editor-styles-wrapper a,
				.wp-block-freeform.block-library-rich-text__tinymce a {
					color: ' . $silicon_theme_mods['accent_color'] . ';
				}

				.editor-styles-wrapper a:focus,
				.wp-block-freeform.block-library-rich-text__tinymce a:focus {
					outline-color: ' . $silicon_theme_mods['accent_color'] . ';
				}

				body.post-type-post .editor-post-title__block::after {
					content: "";
				}';
			}

			$styles .= $this->gutenberg_get_css();

			wp_add_inline_style( 'silicon-gutenberg-blocks', apply_filters( 'silicon_gutenberg_block_editor_customizer_css', $styles ) );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			wp_add_inline_style( 'silicon-style', $this->get_css() );
		}

		/**
		 * Layout classes
		 * Adds 'right-sidebar' and 'left-sidebar' classes to the body tag
		 *
		 * @param  array $classes current body classes.
		 * @return string[]          modified body classes
		 * @since  1.0.0
		 */
		public function layout_class( $classes ) {
			$left_or_right = get_theme_mod( 'silicon_layout' );

			$classes[] = $left_or_right . '-sidebar';

			return $classes;
		}

		/**
		 * Add CSS for custom controls
		 *
		 * This function incorporates CSS from the Kirki Customizer Framework
		 *
		 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
		 * is licensed under the terms of the GNU GPL, Version 2 (or later)
		 *
		 * @link https://github.com/reduxframework/kirki/
		 * @since  1.5.0
		 */
		public function customizer_custom_control_css() {
			?>
			<style>
			.customize-control-radio-image input[type=radio] {
				display: none;
			}

			.customize-control-radio-image label {
				display: block;
				width: 48%;
				float: left;
				margin-right: 4%;
			}

			.customize-control-radio-image label:nth-of-type(2n) {
				margin-right: 0;
			}

			.customize-control-radio-image img {
				opacity: .5;
			}

			.customize-control-radio-image input[type=radio]:checked + label img,
			.customize-control-radio-image img:hover {
				opacity: 1;
			}

			</style>
			<?php
		}

		/**
		 * Get site logo.
		 *
		 * @since 2.1.5
		 * @return string
		 */
		public function get_site_logo() {
			return silicon_site_title_or_logo( false );
		}

		/**
		 * Get site name.
		 *
		 * @since 2.1.5
		 * @return string
		 */
		public function get_site_name() {
			return get_bloginfo( 'name', 'display' );
		}

		/**
		 * Get site description.
		 *
		 * @since 2.1.5
		 * @return string
		 */
		public function get_site_description() {
			return get_bloginfo( 'description', 'display' );
		}

		/**
		 * Check if current page is using the Homepage template.
		 *
		 * @since 2.3.0
		 * @return bool
		 */
		public function is_homepage_template() {
			$template = get_post_meta( get_the_ID(), '_wp_page_template', true );

			if ( ! $template || 'template-homepage.php' !== $template || ! has_post_thumbnail( get_the_ID() ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Customize site Custom Theme Color
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_customcolor( $wp_customize ) {
			/*
			 * Custom Color Enable / Disble Toggle
			 */
			$wp_customize->add_setting(
				'silicon_enable_custom_color',
				[
					'default'           => 'no',
					'sanitize_callback' => 'sanitize_key',
				]
			);

			$wp_customize->add_control(
				'silicon_enable_custom_color',
				[
					'type'        => 'radio',
					'section'     => 'colors',
					'label'       => esc_html__( 'Enable Custom Color?', 'silicon' ),
					'description' => esc_html__(
						'This settings allow you to apply your custom color option.',
						'silicon'
					),
					'choices'     => [
						'yes' => esc_html__( 'Yes', 'silicon' ),
						'no'  => esc_html__( 'No', 'silicon' ),
					],
				]
			);

			/**
			 * Primary Color
			 */
			$wp_customize->add_setting(
				'silicon_primary_color',
				array(
					'default'           => apply_filters( 'silicon_default_primary_color', '#6366f1' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'silicon_primary_color',
					array(
						'label'           => esc_html__( 'Primary color', 'silicon' ),
						'section'         => 'colors',
						'settings'        => 'silicon_primary_color',
						'active_callback' => function () {
							return get_theme_mod( 'silicon_enable_custom_color', 'no' ) === 'yes';
						},
					)
				)
			);
		}

	}

endif;

return new Silicon_Customizer();
