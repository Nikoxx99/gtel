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
class Skin_Image_Box_V2 extends Skin_Base {

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
		return 'si-image-box-2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Style 2', 'silicon-elementor' );
	}


	/**
	 * Added control of the Content tab.
	 */
	public function add_content_control() {

		$disable_controls = [
			'position',
			'link',

		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-image-box-2',
					],
				]
			);
		}

		$this->parent->update_control(
			'description_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-description' => 'color: {{VALUE}};',

				],
				'selectors' => [
					'{{WRAPPER}} .sn-description' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'title_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-image-box-title' => 'color: {{VALUE}};',

				],
				'selectors' => [
					'{{WRAPPER}} .sn-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'title_typography_typography',
			[

				'selector' => [ '{{WRAPPER}} .elementor-image-box-title' ],
				'selector' => [

					'{{WRAPPER}} .sn-title',
				],

			]
		);

		$this->parent->update_control(
			'description_typography_typography',
			[

				'selector' => [
					'{{WRAPPER}} .elementor-image-box-description' => 'font-size: {{SIZE}}{{UNIT}} !important',
				],
				'selector' => [

					'{{WRAPPER}} .sn-description',
				],

			]
		);

		$this->parent->update_control(
			'css_filters_filters',
			[

				'selector' => '{{WRAPPER}} .elementor-image-box-img img',
				'selector' => [

					'{{WRAPPER}} .sn-img',
				],

			]
		);

		$this->parent->update_control(
			'description_shadow',
			[

				'selector' => '{{WRAPPER}} .elementor-image-box-description',
				'selector' => '{{WRAPPER}} .sn-description',
			]
		);

		$this->parent->start_injection(
			[
				'of' => 'title_size',
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
				'default'          => [
					'value' => 'bx  bx-right-arrow-circle',
				// 'library' => 'fa-solid',
				],

			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'text-primary fs-3 ms-2',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title icon', 'silicon-elementor' ),

			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Update control of the style tab.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 */
	public function modifying_style_sections( Elementor\Widget_Base $widget ) {

		$this->parent     = $widget;
		$disable_controls = [
			'text_align',
			'content_vertical_alignment',
			'title_bottom_space',
			'hover_animation',
			'image_space',
			'image_size',
			'image_effects',
			'image_border_radius',
			'normal',
			// 'hover',
			'image_opacity',
			'background_hover_transition',

		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-image-box-2',
					],
				]
			);
		}

		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'Card', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'section_style_card',
			]
		);

		$this->add_control(
			'card_class',
			[
				'label'       => esc_html__( 'Card Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'text-decoration-none pt-5 px-sm-3 px-md-0 px-lg-3 pb-sm-3 pb-md-0 pb-lg-3 me-xl-2',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'sn_link',
			[
				'label'       => esc_html__( 'Link', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'show_hover',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'CardHover', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_border',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Border Disable', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'No', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Yes', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'enable_card_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Card Shadow', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'card_body_class',
			[
				'label'       => esc_html__( 'Card Body Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'pt-3',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->end_controls_section();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'section_style_image',
			]
		);

		$this->add_control(
			'wrap_class',
			[
				'label'       => esc_html__( 'Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'elementor-image-box-img rounded-3 position-absolute top-0 translate-middle-y p-3',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'wrap_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-silicon-wrap' => 'background-color: {{VALUE}} !important;',
				],
				'default'   => '#6366F1',

			]
		);

		$this->add_control(
			'image_wrap_css',
			[
				'label'       => esc_html__( 'Shadow Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'shadow-primary',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'm-1 w-40 h-auto',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'heading_title',
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'h4 d-inline-flex align-items-center',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'heading_description',
			]
		);

		$this->add_control(
			'description_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for icon  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'text-body mb-0',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
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
			'card_class',
			'sn_link',
			'show_hover',
			'show_border',
			'enable_card_shadow',
			'card_body_class',
			'wrap_class',
			'wrap_bg_color',
			'enable_shadow',
			'image_class',
			'image_wrap_css',
			'title_class',
			'description_class',
			'selected_icon',
			'icon_class',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$card_class = [ 'card', 'h-100' ];

		if ( 'yes' === $skin_settings['show_hover'] ) {
				$card_class[] = 'card-hover';
		}
		if ( 'yes' === $skin_settings['show_border'] ) {
			$card_class[] = 'border-0';
		}
		if ( 'yes' === $skin_settings['enable_card_shadow'] ) {
			$card_class[] = 'shadow-sm';
		}

		if ( $skin_settings['card_class'] ) {
			$card_class[] = $skin_settings['card_class'];
		}

		$parent->add_render_attribute(
			'card_attribute',
			[
				'class' => $card_class,

			]
		);
		$parent->add_link_attributes(
			'card_attribute',
			$skin_settings['sn_link']
		);

		$card_body_class = [ 'card-body' ];

		if ( $skin_settings['card_body_class'] ) {
			$card_body_class[] = $skin_settings['card_body_class'];
		}

		$parent->add_render_attribute(
			'card_body_attribute',
			[
				'class' => $card_body_class,

			]
		);

		$wrap_class = [ 'd-inline-block ', 'sn-silicon-wrap' ];
		// $wrap_class[] =  $skin_settings['wrap_bg_color'];
		// if ( 'yes' === $skin_settings['enable_shadow'] ) {
		// $wrap_class[] =  $skin_settings['image_wrap_css'];
		// }

		if ( $skin_settings['wrap_class'] ) {
			$wrap_class[] = $skin_settings['wrap_class'];
		}
		if ( $skin_settings['image_wrap_css'] ) {
			$wrap_class[] = $skin_settings['image_wrap_css'];
		}

		$parent->add_render_attribute(
			'wrap_attribute',
			[
				'class' => $wrap_class,

			]
		);

		$image_class = [ 'd-block' ];

		if ( $skin_settings['image_class'] ) {
			$image_class[] = $skin_settings['image_class'];
		}

		$title_class   = [ 'sn-title', 'elementor-image-box-title' ];
		$title_class[] = $skin_settings['title_class'];

		$parent->add_render_attribute(
			'title_attribute',
			[
				'class' => $title_class,

			]
		);

		$description_class   = [ 'sn-description', 'elementor-image-box-description' ];
		$description_class[] = $skin_settings['description_class'];

		$parent->add_render_attribute(
			'description_attribute',
			[
				'class' => $description_class,

			]
		);

		$icon_class   = [];
		$icon_class[] = $skin_settings['icon_class'];
		$icon_class[] = $skin_settings['selected_icon']['value'];

		$parent->add_render_attribute(
			'icon_attribute',
			[

				'class' => $icon_class,

			]
		);

		?>
				<a <?php $parent->print_render_attribute_string( 'card_attribute' ); ?>>
					<div <?php $parent->print_render_attribute_string( 'card_body_attribute' ); ?>>
						<div <?php $parent->print_render_attribute_string( 'wrap_attribute' ); ?>>
							<?php
							$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ) );
							echo wp_kses_post( SNUtils::add_class_to_image_html( $image_html, $image_class ) );
							?>
						</div>
						<<?php echo esc_html( $settings['title_size'] ); ?> <?php $parent->print_render_attribute_string( 'title_attribute' ); ?>>
							<?php echo esc_html( $settings['title_text'] ); ?>
							<i <?php $parent->print_render_attribute_string( 'icon_attribute' ); ?>></i>
						</<?php echo esc_html( $settings['title_size'] ); ?>>
						<p <?php $parent->print_render_attribute_string( 'description_attribute' ); ?>><?php echo wp_kses_post( $settings['description_text'] ); ?></p>
					</div>
				</a>
			
	
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
