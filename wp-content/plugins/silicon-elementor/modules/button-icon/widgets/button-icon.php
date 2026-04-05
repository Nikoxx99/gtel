<?php
namespace SiliconElementor\Modules\ButtonIcon\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Button Icon Widget
 */
class Button_Icon extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'si-button-icon';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Button Icon', 'silicon-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'button-icon' ];
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
			]
		);
		$this->add_control(
			'display_style',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Styles', 'silicon-elementor' ),
				'default' => 'default',
				'options' => [
					'default'  => esc_html__( 'Style 1', 'silicon-elementor' ),
					'style_v2' => esc_html__( 'Style 2', 'silicon-elementor' ),
				],
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'primary',
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
					'display_style' => 'default',
				],
			]
		);

		$this->add_control(
			'button_variant',
			[
				'label'     => esc_html__( 'Variant', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''        => esc_html__( 'Default', 'silicon-elementor' ),
					'outline' => esc_html__( 'Outline', 'silicon-elementor' ),
				],
				'condition' => [
					'display_style' => 'default',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],
				'condition' => [
					'display_style' => 'default',
				],
			]
		);

		$this->add_control(
			'button_shape',
			[
				'label'     => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					'rounded-circle' => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill'   => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'      => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [
					'display_style' => 'default',
				],
			]
		);

		$this->add_control(
			'button_icon_sn',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value' => 'bx bx-chevron-down',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => esc_html__( 'Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Click here', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Click here', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			[
				'label'      => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'wrap_class',
			[
				'label'       => esc_html__( 'Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
				'default'     => esc_html__( 'position-absolute d-none d-lg-block bottom-0 start-0 w-100', 'silicon-elementor' ),
				'condition'   => [
					'display_style' => 'style_v2',
				],

			]
		);

		$this->add_control(
			'button_class_style_v2',
			[
				'label'       => esc_html__( 'Link Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'nav-link stretched-link text-light fw-normal opacity-80 py-2 px-3', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
				'seperator'   => 'after',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => [
					'{{WRAPPER}} .silicon_button_text' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' =>
					'{{WRAPPER}} .silicon_button_text',

			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn_button_icon' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'selector' =>
					'{{WRAPPER}} .sn_button_icon',

			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Button Icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$button_classes = [ 'btn', 'btn-icon', 'sn-icon__button' ];

		if ( ! empty( $settings['button_type'] ) ) {
			if ( '' === $settings['button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['button_type'];
			} elseif ( 'outline' === $settings['button_variant'] ) {
				$button_classes[] = 'btn-' . $settings['button_variant'] . '-' . $settings['button_type'];
			}
		}

		if ( ! empty( $settings['button_size'] ) ) {
			$button_classes[] = 'btn-' . $settings['button_size'];
		}

		if ( ! empty( $settings['button_shape'] ) ) {
			$button_classes[] = $settings['button_shape'];
		}

		$anchor_class = [ 'silicon_button_text' ];
		if ( ! empty( $settings['button_class_style_v2'] ) ) {
			$anchor_class[] = $settings['button_class_style_v2'];
		}

		$button_icon_class = [ 'sn_button_icon' ];

		if ( ! empty( $settings['icon_class'] ) ) {
			$button_icon_class[] = $settings['icon_class'];
		}

		if ( $settings['button_icon_sn']['value'] ) {
			$button_icon_class[] = $settings['button_icon_sn']['value'];
		}

		$this->add_render_attribute( 'button_icon', 'class', $button_classes );
		$this->add_render_attribute( 'button_icon_sn', 'class', $button_icon_class );
		$this->add_render_attribute( 'button_link', 'class', $anchor_class );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_link', $settings['link'] );
		}
		?>
		<?php if ( 'default' === $settings['display_style'] ) : ?>
		<div class="position-relative d-flex align-items-center justify-content-center justify-content-md-start">
			<div <?php $this->print_render_attribute_string( 'button_icon' ); ?>>
				<i <?php $this->print_render_attribute_string( 'button_icon_sn' ); ?>></i>
			</div>
			<?php
			if ( ! empty( $settings['text'] ) ) :
				?>
				<a <?php $this->print_render_attribute_string( 'button_link' ); ?>><?php echo esc_html( $settings['text'] ); ?></a>
			<?php endif; ?>
		</div>
			<?php
		endif;
		?>
		<?php if ( 'style_v2' === $settings['display_style'] ) : ?>
		<div class="<?php echo esc_attr( $settings['wrap_class'] ); ?>">
			<div class="container pb-4 mb-3 mb-xxl-0 px-2">
			<?php
			if ( ! empty( $settings['text'] ) ) :
				?>
				<a <?php $this->print_render_attribute_string( 'button_link' ); ?>>
					<span class="d-flex align-items-center ">
						<?php echo esc_html( $settings['text'] ); ?>
						<i <?php $this->print_render_attribute_string( 'button_icon_sn' ); ?>></i>
					</span>
				</a>
			<?php endif; ?>
			</div>
		</div>
			<?php
		endif;
	}
}
