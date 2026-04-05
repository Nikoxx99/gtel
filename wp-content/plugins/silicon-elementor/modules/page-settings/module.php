<?php
namespace SiliconElementor\Modules\PageSettings;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Core\DocumentTypes\PageBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor page templates module.
 *
 * Elementor page templates module handler class is responsible for registering
 * and managing Elementor page templates modules.
 *
 * @since 1.0.0
 */
class Module extends BaseModule {

	/**
	 * Post Id,.
	 *
	 * @var Plugin
	 */
	protected $post_id = 0;
	/**
	 * Page Options.
	 *
	 * @var Plugin
	 */
	protected $sn_page_options = [];
	/**
	 * Static Content
	 *
	 * @var Plugin
	 */
	protected $static_contents = [];

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->sn_page_options = $this->sn_page_options();
		$this->static_contents = function_exists( 'silicon_static_content_options' ) ? silicon_static_content_options() : [];
		add_action( 'elementor/documents/register_controls', [ $this, 'action_register_template_control' ] );
		add_action( 'elementor/element/wp-post/section_page_style/before_section_end', [ $this, 'add_body_style_controls' ] );
		add_filter( 'update_post_metadata', [ $this, 'filter_update_meta' ], 10, 5 );
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-page-settings';
	}

	/**
	 * Add Body Styles Controls.
	 *
	 * @param array $document The Document.
	 * @return void
	 */
	public function add_body_style_controls( $document ) {

		$document->add_control(
			'enable_overflow',
			[
				'label'     => esc_html__( 'Enable Overflow?', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'body' => 'overflow-x:visible !important;',
				],
			]
		);
	}

	/**
	 * Get Name.
	 *
	 * @return array
	 */
	public function get_special_settings_names() {
		return [
			// General..
			'general_body_classes',

			// Header.
			'header_silicon_enable_custom_header',
			'header_silicon_select_custom_sticky',
			'header_silicon_enable_primary_nav_button',
			'header_silicon_buy_button_icon',
			'header_silicon_buy_button_text',
			'header_silicon_buy_button_link',
			'header_silicon_buy_button_type',
			'header_silicon_buy_button_shape',
			'header_silicon_buy_button_size',
			'header_silicon_button_heading',
			'header_silicon_button_text_color',
			'header_silicon_button_bg_color',
			'header_silicon_button_text_hover',
			'header_silicon_button_hover_color',
			'header_silicon_enable_shadow',
			'header_silicon_disable_dark_shadow',
			'header_silicon_border',
			'header_silicon_border_color',
			'header_silicon_buy_button_css',
			'header_silicon_select_navbar_position',
			'header_silicon_enable_sticky',
			'header_silicon_select_navbar_text',
			'header_silicon_select_background',

			// Footer.
			'footer_silicon_enable_custom_footer',
			'footer_silicon_footer_variant',
			'footer_silicon_static_widgets',
			'footer_silicon_copyright_text',
		];
	}

	/**
	 * Update Silicon Page Options.
	 *
	 * @param array $object_id Id.
	 * @param array $special_settings settings.
	 * @return void
	 */
	public function update_sn_page_option( $object_id, $special_settings ) {
		$_sn_page_options = $this->sn_page_options( $object_id );
		$sn_page_options  = ! empty( $_sn_page_options ) ? $_sn_page_options : [];

		$general_option_key     = 'general';
		$header_option_key      = 'header';
		$footer_option_key      = 'footer';
		$len_general_option_key = strlen( $general_option_key . '_' );
		$len_header_option_key  = strlen( $header_option_key . '_' );
		$len_footer_option_key  = strlen( $footer_option_key . '_' );

		foreach ( $special_settings as $key => $value ) {
			if ( substr( $key, 0, $len_general_option_key ) === $general_option_key . '_' ) {
				if ( ! isset( $sn_page_options[ $general_option_key ] ) ) {
					$sn_page_options[ $general_option_key ] = [];
				}
				$sn_page_options[ $general_option_key ][ substr( $key, $len_general_option_key ) ] = $value;
			} elseif ( substr( $key, 0, $len_header_option_key ) === $header_option_key . '_' ) {
				if ( ! isset( $sn_page_options[ $header_option_key ] ) ) {
					$sn_page_options[ $header_option_key ] = [];
				}
				$sn_page_options[ $header_option_key ][ substr( $key, $len_header_option_key ) ] = $value;
			} elseif ( substr( $key, 0, $len_footer_option_key ) === $footer_option_key . '_' ) {
				if ( ! isset( $sn_page_options[ $footer_option_key ] ) ) {
					$sn_page_options[ $footer_option_key ] = [];
				}
				$sn_page_options[ $footer_option_key ][ substr( $key, $len_footer_option_key ) ] = $value;
			} else {
				$sn_page_options[ $key ] = $value;
			}
		}

		if ( ! empty( $sn_page_options ) ) {
			$this->sn_page_options = $sn_page_options;
			update_metadata( 'post', $object_id, '_sn_page_options', $sn_page_options );
		}
	}

	/**
	 * Get Page Options.
	 *
	 * @param array  $option_name name.
	 * @param string $option_group group.
	 * @param string $default default.
	 * @return array
	 */
	public function get_sn_page_options( $option_name, $option_group = '', $default = '' ) {
		$sn_page_options = $this->sn_page_options();

		if ( ! empty( $option_group ) && ! empty( $option_name ) ) {
			if ( isset( $sn_page_options[ $option_group ] ) && isset( $sn_page_options[ $option_group ][ $option_name ] ) ) {
				return $sn_page_options[ $option_group ][ $option_name ];
			}
		} elseif ( empty( $option_group ) && ! empty( $option_name ) ) {
			if ( isset( $sn_page_options[ $option_name ] ) ) {
				return $sn_page_options[ $option_name ];
			}
		}

		return $default;
	}

	/**
	 * Get Page Options.
	 *
	 * @param array $post_id post ID.
	 * @return array
	 */
	public function sn_page_options( $post_id = null ) {
		if ( ! empty( $this->sn_page_options ) ) {
			return $this->sn_page_options;
		}

		if ( ! $post_id ) {
			$post_id = $this->post_id;
		}

		$clean_meta_data = get_post_meta( $post_id, '_sn_page_options', true );
		$sn_page_options = maybe_unserialize( $clean_meta_data );

		if ( empty( $sn_page_options ) ) {
			$sn_page_options = [];
		} elseif ( ! empty( $sn_page_options ) && ! is_array( $sn_page_options ) ) {
			$sn_page_options = [];
		}

		$this->sn_page_options = $sn_page_options;
		return $sn_page_options;
	}

	/**
	 * Register template control.
	 *
	 * Adds custom controls to any given document.
	 *
	 * Fired by `update_post_metadata` action.
	 *
	 * @since 1.0.0
	 *
	 * @param Document $document The document instance.
	 */
	public function action_register_template_control( $document ) {
		$post_types = function_exists( 'silicon_option_enabled_post_types' ) ? silicon_option_enabled_post_types() : [ 'post', 'page' ];
		if ( $document instanceof PageBase && is_a( $document->get_main_post(), 'WP_Post' ) && in_array( $document->get_main_post()->post_type, $post_types, true ) ) {
			$this->post_id = $document->get_main_post()->ID;
			$this->register_template_control( $document );
		}
	}

	/**
	 * Register template control.
	 *
	 * @param Document $page   The document instance.
	 */
	public function register_template_control( $page ) {
		// $this->add_general_controls( $page, 'general' );
		$this->add_header_controls( $page, 'header' );
		$this->add_footer_controls( $page, 'footer' );
	}

	/**
	 * Add General Controls.
	 *
	 * @param Document $page Page.
	 * @param string   $option_group group.
	 * @return void
	 */
	public function add_general_controls( Document $page, $option_group = '' ) {
		$page->start_injection(
			[
				'of'       => 'post_status',
				'fallback' => [
					'of' => 'post_title',
				],
			]
		);

		$page->add_control(
			'general_body_classes',
			[
				'label'   => esc_html__( 'Body Classes', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $this->get_sn_page_options( 'body_classes', $option_group ),
			]
		);

		$page->end_injection();
	}

	/**
	 * Add Header Controls.
	 *
	 * @param Document $page Page.
	 * @param string   $option_group group.
	 * @return void
	 */
	public function add_header_controls( Document $page, $option_group = '' ) {
		$page->start_controls_section(
			'document_settings_header',
			[
				'label' => esc_html__( 'Headers', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$page->add_control(
			'header_silicon_enable_custom_header',
			[
				'label'     => esc_html__( 'Custom Header', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_enable_custom_header', $option_group, 'no' ),
				'label_on'  => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$page->add_control(
			'header_silicon_select_navbar_position',
			[
				'label'     => esc_html__( 'Navbar Position', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_select_navbar_position', $option_group, 'default' ),
				'options'   => [
					'default'           => esc_html__( 'Default', 'silicon-elementor' ),
					'fixed-top'         => esc_html__( 'Fixed Top', 'silicon-elementor' ),
					'position-absolute' => esc_html__( 'Absolute', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_enable_sticky',
			[
				'label'     => esc_html__( 'Stick Navbar on Scroll', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_enable_sticky', $option_group, 'no' ),
				'label_on'  => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header'    => 'yes',
					'header_silicon_select_navbar_position!' => 'fixed-top',
				],
			]
		);

		$page->add_control(
			'header_silicon_select_navbar_text',
			[
				'label'     => esc_html__( 'Navbar Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_select_navbar_text', $option_group, 'no' ),
				'label_on'  => esc_html__( 'Dark', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Light', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header'    => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_select_background',
			[
				'label'     => esc_html__( 'Background', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_select_background', $option_group, 'default' ),
				'options'   => [
					'default'  => esc_html__( 'Transparent', 'silicon-elementor' ),
					'bg-light' => esc_html__( 'Light', 'silicon-elementor' ),
					'bg-dark'  => esc_html__( 'Dark', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_enable_shadow',
			[
				'label'     => esc_html__( 'Enable shadow', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_enable_shadow', $option_group ),
				'label_on'  => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_disable_dark_shadow',
			[
				'label'     => esc_html__( 'Disable Dark Mode Shadow', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_disable_dark_shadow', $option_group ),
				'label_on'  => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_shadow'        => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_border',
			[
				'label'     => esc_html__( 'Border', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_border', $option_group, 'none' ),
				'options'   => [
					'none'   => esc_html__( 'None', 'silicon-elementor' ),
					'top'    => esc_html__( 'Top', 'silicon-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'silicon-elementor' ),
					'start'  => esc_html__( 'Start', 'silicon-elementor' ),
					'end'    => esc_html__( 'End', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_border_color', $option_group, 'light' ),
				'options'   => [
					'primary'   => esc_html__( 'Primary', 'silicon-elementor' ),
					'secondary' => esc_html__( 'Secondary', 'silicon-elementor' ),
					'success'   => esc_html__( 'Success', 'silicon-elementor' ),
					'danger'    => esc_html__( 'Danger', 'silicon-elementor' ),
					'warning'   => esc_html__( 'Warning', 'silicon-elementor' ),
					'info'      => esc_html__( 'Info', 'silicon-elementor' ),
					'light'     => esc_html__( 'Light', 'silicon-elementor' ),
					'dark'      => esc_html__( 'Dark', 'silicon-elementor' ),
					'link'      => esc_html__( 'Link', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_border!'              => 'none',
				],
			]
		);

		$page->add_control(
			'header_silicon_enable_primary_nav_button',
			[
				'label'     => esc_html__( 'Enable Buy Now Button ?', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_enable_primary_nav_button', $option_group ),
				'label_on'  => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_icon',
			[
				'label'                  => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'skin'                   => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'label_block'            => false,
				'default'                => [
					'value' => $this->get_sn_page_options( 'silicon_buy_button_icon', $option_group, 'bx bx-cart' ),
				],
				'condition'              => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_text',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $this->get_sn_page_options( 'silicon_buy_button_text', $option_group, 'Buy now' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_link',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $this->get_sn_page_options( 'silicon_buy_button_link', $option_group, '#' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_buy_button_type', $option_group, 'primary' ),
				'options'   => [
					'primary'   => esc_html__( 'Primary', 'silicon-elementor' ),
					'secondary' => esc_html__( 'Secondary', 'silicon-elementor' ),
					'success'   => esc_html__( 'Success', 'silicon-elementor' ),
					'danger'    => esc_html__( 'Danger', 'silicon-elementor' ),
					'warning'   => esc_html__( 'Warning', 'silicon-elementor' ),
					'info'      => esc_html__( 'Info', 'silicon-elementor' ),
					'light'     => esc_html__( 'Light', 'silicon-elementor' ),
					'dark'      => esc_html__( 'Dark', 'silicon-elementor' ),
					'link'      => esc_html__( 'Link', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_shape',
			[
				'label'     => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_buy_button_shape', $option_group, '' ),
				'options'   => [
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $this->get_sn_page_options( 'silicon_buy_button_size', $option_group, 'sm' ),
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->add_control(
			'header_silicon_buy_button_css',
			[
				'label'     => esc_html__( 'Button CSS', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $this->get_sn_page_options( 'silicon_buy_button_css', $option_group, 'fs-sm' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
			]
		);

		$page->end_controls_section();

		$page->start_controls_section(
			'document_styles_header',
			[
				'label'     => esc_html__( 'Headers', 'silicon-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

		$page->start_controls_tabs( 'header_silicon_tabs_header_style' );

		$page->start_controls_tab(
			'header_silicon_header_normal',
			[
				'label'     => esc_html__( 'Normal', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

			$page->add_control(
				'header_silicon_text_color',
				[
					'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .site-header' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nav-link' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .form-check-label' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .navbar-brand' => 'color: {{VALUE}} !important;',
					],
					'default'   => $this->get_sn_page_options( 'silicon_text_color', $option_group ),
				]
			);

		$page->end_controls_tab();

		$page->start_controls_tab(
			'header_silicon_header_hover',
			[
				'label'     => esc_html__( 'Hover', 'silicon-elementor' ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
			]
		);

			$page->add_control(
				'header_silicon_text_hover',
				[
					'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .site-header:hover, {{WRAPPER}} .site-header:focus' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nav-link:hover, {{WRAPPER}} .nav-link:focus' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .form-check-label:hover, {{WRAPPER}} .form-check-label:focus' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .navbar-brand:hover, {{WRAPPER}} .navbar-brand:focus' => 'color: {{VALUE}} !important;',
					],
					'default'   => $this->get_sn_page_options( 'silicon_text_hover', $option_group ),
					'condition' => [
						'header_silicon_enable_custom_header' => 'yes',
					],
				]
			);

		$page->end_controls_tab();

		$page->end_controls_tabs();

		$page->add_control(
			'header_silicon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .site-header' => 'background-color: {{VALUE}} !important;',
				],
				'default'   => $this->get_sn_page_options( 'silicon_bg_color', $option_group ),
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$page->add_control(
			'header_silicon_button_heading',
			[
				'label'     => esc_html__( 'Button', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'header_silicon_enable_custom_header' => 'yes',
					'header_silicon_enable_primary_nav_button' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$page->start_controls_tabs( 'header_silicon_tabs_button_style' );

			$page->start_controls_tab(
				'header_silicon_tab_button_normal',
				[
					'label'     => esc_html__( 'Normal', 'silicon-elementor' ),
					'condition' => [
						'header_silicon_enable_custom_header' => 'yes',
						'header_silicon_enable_primary_nav_button' => 'yes',
					],
				]
			);

				$page->add_control(
					'header_silicon_button_text_color',
					[
						'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sn-header-button' => 'color: {{VALUE}} !important;',
						],
						'default'   => $this->get_sn_page_options( 'silicon_button_text_color', $option_group ),
						'condition' => [
							'header_silicon_enable_custom_header' => 'yes',
							'header_silicon_enable_primary_nav_button' => 'yes',
						],
					]
				);

				$page->add_control(
					'header_silicon_button_bg_color',
					[
						'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sn-header-button' => 'background-color: {{VALUE}} !important;',
						],
						'default'   => $this->get_sn_page_options( 'silicon_button_bg_color', $option_group ),
						'condition' => [
							'header_silicon_enable_custom_header' => 'yes',
							'header_silicon_enable_primary_nav_button' => 'yes',
						],
					]
				);

			$page->end_controls_tab();

			$page->start_controls_tab(
				'header_silicon_tab_button_hover',
				[
					'label'     => esc_html__( 'Hover', 'silicon-elementor' ),
					'condition' => [
						'header_silicon_enable_custom_header' => 'yes',
						'header_silicon_enable_primary_nav_button' => 'yes',
					],
				]
			);

				$page->add_control(
					'header_silicon_button_text_hover',
					[
						'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sn-header-button:hover, {{WRAPPER}} .sn-header-button-hover:focus' => 'color: {{VALUE}} !important;',
						],
						'default'   => $this->get_sn_page_options( 'silicon_button_text_hover', $option_group ),
						'condition' => [
							'header_silicon_enable_custom_header' => 'yes',
							'header_silicon_enable_primary_nav_button' => 'yes',
						],
					]
				);

				$page->add_control(
					'header_silicon_button_hover_color',
					[
						'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sn-header-button:hover' => 'background-color: {{VALUE}} !important;',
						],
						'default'   => $this->get_sn_page_options( 'silicon_button_hover_color', $option_group ),
						'condition' => [
							'header_silicon_enable_custom_header' => 'yes',
							'header_silicon_enable_primary_nav_button' => 'yes',
						],
					]
				);

			$page->end_controls_tab();

		$page->end_controls_tabs();

		$page->end_controls_section();
	}

	/**
	 * Add Footer Controls.
	 *
	 * @param Document $page Page.
	 * @param string   $option_group group.
	 * @return void
	 */
	public function add_footer_controls( Document $page, $option_group = '' ) {
		$page->start_controls_section(
			'document_settings_footer',
			[
				'label' => esc_html__( 'Footer', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$page->add_control(
			'footer_silicon_enable_custom_footer',
			[
				'label'     => esc_html__( 'Custom Footer', 'silicon-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => $this->get_sn_page_options( 'silicon_enable_custom_footer', $option_group, 'no' ),
				'label_on'  => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$page->add_control(
			'footer_silicon_footer_variant',
			[
				'label'     => esc_html__( 'Footer Variant', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'           => esc_html__( 'Default', 'silicon-elementor' ),
					'static-content' => esc_html__( 'Static Footer', 'silicon-elementor' ),
					'no-footer'      => esc_html__( 'None', 'silicon-elementor' ),
				],
				'default'   => $this->get_sn_page_options( 'silicon_footer_variant', $option_group, 'none' ),
				'condition' => [
					'footer_silicon_enable_custom_footer' => 'yes',
				],
			]
		);

		if ( function_exists( 'silicon_is_mas_static_content_activated' ) && silicon_is_mas_static_content_activated() ) {
			$page->add_control(
				'footer_silicon_static_widgets',
				[
					'label'     => esc_html__( 'Footer Static Widgets', 'silicon-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $this->static_contents,
					'condition' => [
						'footer_silicon_enable_custom_footer' => 'yes',
						'footer_silicon_footer_variant' => 'static-content',
					],
					'default'   => $this->get_sn_page_options( 'silicon_static_widgets', $option_group, '' ),
				]
			);
		}

		$page->add_control(
			'footer_silicon_copyright_text',
			[
				'label'     => esc_html__( 'Copyright Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => $this->get_sn_page_options( 'silicon_copyright_text', $option_group, wp_kses_post( __( '© All rights reserved. Made with <i class="bx bx-heart d-inline-block fs-lg text-gradient-primary align-middle mt-n1 mx-1"></i> by&nbsp; <a href="https://madrasthemes.com/" class="text-muted" target="_blank" rel="noopener">MadrasThemes</a>', 'silicon-elementor' ) ) ),
				'condition' => [
					'footer_silicon_enable_custom_footer' => 'yes',
					'footer_silicon_footer_variant!'      => 'static-content',
				],
			]
		);

		$page->end_controls_section();
	}

	/**
	 * Filter metadata update.
	 *
	 * Filters whether to update metadata of a specific type.
	 *
	 * Elementor don't allow WordPress to update the parent page template
	 * during `wp_update_post`.
	 *
	 * Fired by `update_{$meta_type}_metadata` filter.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $check     Whether to allow updating metadata for the given type.
	 * @param int    $object_id Object ID.
	 * @param string $meta_key  Meta key.
	 * @param string $meta_value  Meta Value.
	 * @param string $prev_value  previous value.
	 *
	 * @return bool Whether to allow updating metadata of a specific type.
	 */
	public function filter_update_meta( $check, $object_id, $meta_key, $meta_value, $prev_value ) {
		if ( '_elementor_page_settings' === $meta_key ) {
			$current_check = $check;
			if ( ! empty( $meta_value ) && is_array( $meta_value ) ) {
				$special_settings_names = $this->get_special_settings_names();
				$special_settings       = [];
				foreach ( $special_settings_names as $name ) {
					if ( isset( $meta_value[ $name ] ) ) {
						$special_settings[ $name ] = $meta_value[ $name ];
						unset( $meta_value[ $name ] );
						$current_check = false;
					}
				}
				if ( false === $current_check ) {
					update_metadata( 'post', $object_id, $meta_key, $meta_value, $prev_value );
					$this->update_sn_page_option( $object_id, $special_settings );
					return $current_check;
				}
			}
		}

		return $check;
	}
}
