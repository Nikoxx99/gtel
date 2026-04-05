<?php

namespace SiliconElementor\Modules\Button\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Utils;

// Group Controls.
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Group Values.
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

/**
 * Skin Button Market
 */
class Skin_Button_Market extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Widget_Base $parent ) {
		parent::__construct( $parent );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'button-market-silicon';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Market Button', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/button/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/button/section_button/before_section_end', [ $this, 'register_market_button_content_controls' ], 15 );
		// add_action( 'elementor/element/button/section_style/after_section_end', [ $this, 'register_market_button_style_controls'], 15 );.
	}

	/**
	 * Register market button content controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function register_market_button_content_controls( $widget ) {

		$update_control_ids = [ 'selected_icon', 'icon_align', 'icon_indent', 'view', 'text' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'button-market-silicon', 'button-reviews-silicon' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);

		$this->add_control(
			'image_light',
			[
				'label'   => esc_html__( 'Image Light', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'image_dark',
			[
				'label'   => esc_html__( 'Image Dark', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'image_width',
			[
				'label'      => esc_html__( 'Width', 'silicon-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 124,
				],
				'selectors'  => [
					'{{WRAPPER}} .si-width' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_class',
			[
				'label'       => esc_html__( 'Button Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <a> tag', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Render market button style controls.
	 *
	 * @return void
	 */
	public function register_market_button_style_controls() {

		$this->start_controls_section(
			'market_section_style',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'subtitle_heading',
			[
				'label'     => esc_html__( 'Subtitle', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .si-market-subtitle',
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title',
				'label'    => __( 'Typography', 'silicon-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .si-market-title',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Market Button
	 *
	 * @param array $settings The widget settings.
	 * @param array $skin_settings The skin settings.
	 * @return void
	 */
	public function render_market_button( array $settings, array $skin_settings ) {

		$parent = $this->parent;

		$parent->add_render_attribute(
			'image_light',
			array(
				'class' => 'light-mode-img',
				'src'   => $skin_settings['image_light']['url'],
				'width' => $skin_settings['image_width']['size'],
				'alt'   => 'button',
			)
		);

		$parent->add_render_attribute(
			'image_dark',
			array(
				'class' => 'dark-mode-img',
				'src'   => $skin_settings['image_dark']['url'],
				'width' => $skin_settings['image_width']['size'],
				'alt'   => 'button',
			)
		);

		$button = $skin_settings['button_class'];

		$parent->add_render_attribute(
			'button_link',
			array(
				'class' => [ 'btn btn-dark btn-lg px-3 py-2', $button ],
			)
		);

		$parent->add_link_attributes( 'button_link', $settings['link'] );

		?>
		<!-- App Store button -->
		<a <?php $parent->print_render_attribute_string( 'button_link' ); ?>>
			<img <?php $parent->print_render_attribute_string( 'image_light' ); ?>>
			<img <?php $parent->print_render_attribute_string( 'image_dark' ); ?>>
		</a>
		<?php

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {

		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$skin_control_ids = [ 'image_light', 'image_dark', 'image_width', 'button_class' ];
		$skin_settings    = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$this->render_market_button( $settings, $skin_settings );

	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $button widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $button ) {

		if ( 'button' === $button->get_name() ) {
			return '';
		}

		return $content;
	}
}
