<?php
namespace SiliconElementor\Modules\Imagebox\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Plugin;
use Elementor\Control_Media;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Core\Utils as SNUtils;

/**
 * Skin Image-Box Silicon
 */
class Skin_Image_Box extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/image-box/section_image/after_section_end', [ $this, 'add_content_control' ], 10 );
		add_action( 'elementor/element/image-box/section_style_content/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-image-box';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Style 1', 'silicon-elementor' );
	}

	/**
	 * Added control of the Content tab.
	 */
	public function add_content_control() {

		$disable_controls = [
			'',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-image-box',
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

		$this->parent->add_control(
			'display_style',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Styles', 'silicon-elementor' ),
				'default'   => 'default',
				'options'   => [
					'default'  => esc_html__( 'Style 1', 'silicon-elementor' ),
					'style_v2' => esc_html__( 'Style 2', 'silicon-elementor' ),
					'style_v3' => esc_html__( 'Style 3', 'silicon-elementor' ),
				],
				'condition' => [
					'_skin' => 'si-image-box',
				],
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'image',
			]
		);

		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => esc_html__( 'w-25', 'silicon-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'title_size',
			]
		);

		$this->add_control(
			'enable_button',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Click Here', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'link',
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
					$this->get_control_id( 'enable_button' ) => 'yes',
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
					''         => esc_html__( 'Default', 'silicon-elementor' ),
					'outline'  => esc_html__( 'Outline', 'silicon-elementor' ),
					'active'   => esc_html__( 'Active', 'silicon-elementor' ),
					'disabled' => esc_html__( 'Disabled', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
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
					$this->get_control_id( 'enable_button' ) => 'yes',
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
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'shadow',
			[
				'label'        => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'gradient',
			[
				'label'        => esc_html__( 'Enable Gradient', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sn-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-button-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'image',
			]
		);

		$this->add_control(
			'show_card',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_hover',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Hover', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'show_card' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'show_border',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Show border?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'show_card' ) => 'yes',
				],
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Update control of the style tab.
	 */
	public function modifying_style_sections() {

		$disable_controls = [
			'hover_animation',
			'image_effects',
			'normal',
			'hover',
			'image_opacity',
			'background_hover_transition',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin' => '',
					],
				]
			);
		}

		$this->parent->update_control(
			'description_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sn-description' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'title_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sn-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'image_size',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-wrapper .elementor-image-box-img' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->parent->update_control(
			'image_border_radius',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-wrapper img' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'image_space',
			[
				'selectors' => [
					'{{WRAPPER}}.elementor-position-right .elementor-image-box-img' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.elementor-position-left .elementor-image-box-img' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.elementor-position-top .elementor-image-box-img' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
					'(mobile){{WRAPPER}} .elementor-image-box-img' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->parent->start_injection(
			[
				'of' => 'title_color',
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'description_color',
			]
		);

		$this->add_control(
			'description_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'content_vertical_alignment',
			]
		);

		$this->add_control(
			'card_hover_background_color',
			[
				'label'     => esc_html__( 'Card Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'seperator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .si-elementor_top_wrap' => 'background: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'card_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor_top_wrap:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'padding_class',
			[
				'label'       => esc_html__( 'Padding Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'align-items-start', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'card_wrap_class',
			[
				'label'       => esc_html__( 'Card Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'mb-4', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'image_wrap_class',
			[
				'label'       => esc_html__( 'Image Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'rounded-3 p-3 mb-3', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'wrap_class',
			[
				'label'       => esc_html__( 'Content Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();
	}
	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$skin_control_ids = [
			'display_style',
			'button_type',
			'button_variant',
			'button_size',
			'button_shape',
			'shadow',
			'gradient',
			'button_text',
			'selected_icon',
			'icon_align',
			'title_class',
			'description_class',
			'show_card',
			'show_border',
			'show_hover',
			'enable_button',
			'image_class',
			'wrap_class',
			'padding_class',
			'image_wrap_class',
			'card_wrap_class',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		if ( 'yes' === $skin_settings['shadow'] ) {
			$parent->add_render_attribute( 'button', 'class', 'shadow-' . $skin_settings['button_type'] );
		}

		if ( 'yes' === $skin_settings['gradient'] ) {
			$parent->add_render_attribute( 'button', 'class', 'bg-gradient' );
		}

		if ( 'active' === $skin_settings['button_variant'] ) {
			$parent->add_render_attribute( 'button', 'class', 'active' );
		}

		if ( 'disabled' === $skin_settings['button_variant'] ) {
			$parent->add_render_attribute( 'button', 'class', 'disabled' );
		}

		$button_class = [ 'btn', 'silicon-button', 'stretched-link' ];

		if ( 'link' === $skin_settings['button_type'] ) {
			$button_class[] = 'px-0';
		}

		if ( 'outline' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-outline-' . $skin_settings['button_type'];
		} elseif ( 'soft' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'] . '-soft';
		} elseif ( 'link' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'] . '-link';
		} else {
			$button_class[] = 'btn-' . $skin_settings['button_type'];
		}

		if ( ! empty( $skin_settings['button_size'] ) && 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_size'];
		}

		if ( ! empty( $skin_settings['button_shape'] ) && 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = $skin_settings['button_shape'];
		}

		$parent->add_render_attribute( 'button', 'class', $button_class );

		$migrated = isset( $skin_settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $skin_settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$parent->add_render_attribute( 'title', 'class', [ 'sn-title', $skin_settings['title_class'] ] );

		$parent->add_render_attribute( 'desc', 'class', [ 'sn-description', $skin_settings['description_class'] ] );

		$card_class = [ 'si-elementor_top_wrap', 'flex-column', 'flex-sm-row', 'flex-md-column', 'flex-xxl-row', 'align-items-center', 'h-100' ];

		if ( 'style_v2' === $settings['display_style'] ) {
			$card_class = [ 'si-elementor_top_wrap', $skin_settings['card_wrap_class'] ];
		}

		if ( 'style_v3' === $settings['display_style'] ) {
			$card_class = [ 'si-elementor_top_wrap' ];
		}

		if ( 'yes' === $skin_settings['show_card'] ) {
				$card_class[] = 'card';
		}

		if ( 'yes' === $skin_settings['show_border'] ) {
			$card_class[] = 'style_v2' === $settings['display_style'] ? 'border-0' : 'border-primary';
		}

		if ( 'yes' === $skin_settings['show_hover'] ) {
			$card_class[] = 'card-hover';
		}

		$parent->add_render_attribute(
			'card_attribute',
			[
				'class' => $card_class,
			]
		);

		$image_class = [ ' si_elementor__image' ];

		if ( $skin_settings['image_class'] ) {
			$image_class = $skin_settings['image_class'];
		}

		$wrap_class = [ 'si_elementor__content_wrap' ];

		if ( $skin_settings['wrap_class'] ) {
			$wrap_class = $skin_settings['wrap_class'];
		}
		$parent->add_render_attribute(
			'content_attribute',
			[
				'class' => $wrap_class,
			]
		);

		?><div <?php $parent->print_render_attribute_string( 'card_attribute' ); ?>>
			<?php if ( 'style_v2' === $settings['display_style'] ) : ?>
			<div class="card-body d-flex <?php echo esc_attr( $skin_settings['padding_class'] ); ?>">
				<div class="<?php echo esc_attr( $skin_settings['image_wrap_class'] ); ?>">
			<?php endif; ?>
			<?php if ( 'style_v3' === $settings['display_style'] ) : ?>
			<div class="d-flex <?php echo esc_attr( $skin_settings['padding_class'] ); ?>">
				<div class="d-table bg-secondary <?php echo esc_attr( $skin_settings['image_wrap_class'] ); ?>">
			<?php endif; ?>
				<?php
				$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ) );
				echo wp_kses_post( SNUtils::add_class_to_image_html( $image_html, $image_class ) );
				?>
			<?php if ( 'style_v2' === $settings['display_style'] || 'style_v3' === $settings['display_style'] ) : ?>
				</div>
			<?php endif; ?>
			<div <?php $parent->print_render_attribute_string( 'content_attribute' ); ?>>
				<<?php echo esc_html( $settings['title_size'] ); ?> <?php $parent->print_render_attribute_string( 'title' ); ?>><?php echo esc_html( $settings['title_text'] ); ?></<?php echo esc_html( $settings['title_size'] ); ?>>
				<p <?php $parent->print_render_attribute_string( 'desc' ); ?>><?php echo wp_kses_post( $settings['description_text'] ); ?></p>
				<?php if ( 'yes' === $skin_settings['enable_button'] ) : ?>
					<a href="<?php echo esc_url( $settings['link']['url'] ); ?>" <?php $parent->print_render_attribute_string( 'button' ); ?>><?php echo esc_html( $skin_settings['button_text'] ); ?>
					<?php
					if ( $is_new || $migrated ) :
						$icon_atts          = [ 'aria-hidden' => 'true' ];
						$icon_atts['class'] = 'sn-button-icon';
							Icons_Manager::render_icon( $skin_settings['selected_icon'], $icon_atts );
					else :
						?>
						<i class="<?php echo esc_attr( $skin_settings['icon'] ); ?>" aria-hidden="true"></i>
					<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
		<?php if ( 'style_v2' === $settings['display_style'] || 'style_v3' === $settings['display_style'] ) : ?>
			</div>
		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $widget widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $widget ) {
		if ( 'image-box' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}
}
