<?php
namespace SiliconElementor\Modules\Image\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use SiliconElementor\Plugin;
use Elementor\Control_Media;
use Elementor\Repeater;
use Elementor\Utils;
use SiliconElementor\Core\Utils as SNUtils;

/**
 * Skin Image Silicon
 */
class Skin_Image extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/image/section_image/after_section_end', [ $this, 'add_content_control' ], 10 );
		add_action( 'elementor/element/image/section_style_image/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-image';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'LightGallery - Video', 'silicon-elementor' );
	}

	/**
	 * Added control of the Content tab.
	 */
	public function add_content_control() {

		$disable_controls = [
			'link_to',
			'inline_svg',
			'color',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-image',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'image_class',
			]
		);

		$this->add_control(
			'link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Link', 'silicon-elementor' ),
				'default'     => [
					'url' => esc_url( 'https://www.youtube.com/watch?v=LBfAnFX15nc', 'silicon-elementor' ),
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'play_button',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Play Button?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'xl',
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
					'xl' => esc_html__( 'Extra Large', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'play_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value' => 'bx bx-play',
				],
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => [
					$this->get_control_id( 'play_button' ) => 'yes',
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
					$this->get_control_id( 'play_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'play_button_desc_enable',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Description?', 'silicon-elementor' ),
				'default'   => 'no',
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'play_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'play_button_video_title',
			[
				'label'     => esc_html__( 'Enter a Video Titile', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Watch Video', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'play_button_desc_enable' ) => 'yes',
					$this->get_control_id( 'play_button' ) => 'yes',
				],
			]
		);
		$this->add_control(
			'play_button_video_desc',
			[
				'label'     => esc_html__( 'Enter a Video Description', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'See how our app is different ', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'play_button_desc_enable' ) => 'yes',
					$this->get_control_id( 'play_button' ) => 'yes',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Added control of the Content tab.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 */
	public function modifying_style_sections( Elementor\Widget_Base $widget ) {
		$this->parent     = $widget;
		$disable_controls = [
			'',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-image',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'image_box_shadow',
			]
		);

		$this->start_controls_section(
			'section_style_wrapper',
			[
				'label' => esc_html__( 'Wrapper', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'top_wrapper_class',
			[
				'label'       => esc_html__( 'Top Wrapper Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'position-relative rounded-3 overflow-hidden', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the Top wrapper class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'second_wrapper_class',
			[
				'label'       => esc_html__( 'Second Wrapper Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center zindex-5', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the second wrapper class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'span_class',
			[
				'label'       => esc_html__( 'Span Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-35', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the span class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'span_style',
			[
				'label'       => esc_html__( 'Span Style Attribute', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional Style class that you want to apply to the span attributes', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'button_style' );

		$this->start_controls_tab(
			'button_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Button Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .silicon-button' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3e4265',
				'selectors' => [
					'{{WRAPPER}} .silicon-button' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			[
				'label' => esc_html__( 'Hover', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Button Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => [
					'{{WRAPPER}} .silicon-button:hover' => 'background: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Icon Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .silicon-button:hover' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'video_button_class',
			[
				'label'       => esc_html__( 'Video Button Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'btn btn-icon btn-video silicon-button stretched-link d-flex', 'silicon-elementor' ),
				'separator'   => 'before',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'play_button_video_title_class',
			[
				'label'       => esc_html__( 'Video Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'h6 fs-lg mb-0', 'silicon-elementor' ),
				'separator'   => 'before',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'play_button_video_desc_class',
			[
				'label'       => esc_html__( 'Video Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'text-body fs-sm text-nowrap', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the description', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

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
			'link',
			'play_button',
			'button_size',
			'selected_icon',
			'icon_size',
			'play_button_video_title',
			'play_button_video_desc',
			'play_button_desc_enable',
			'play_button_video_title_class',
			'play_button_video_desc_class',
			'top_wrapper_class',
			'second_wrapper_class',
			'span_class',
			'span_style',
			'video_button_class',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$button_class = [];

		if ( $skin_settings['video_button_class'] ) {
			$button_class[] = $skin_settings['video_button_class'];
		}

		if ( ! empty( $skin_settings['button_size'] ) ) {
			$button_class[] = 'btn-' . $skin_settings['button_size'];
		}

		$link = $skin_settings['link']['url'];

		$parent->add_render_attribute(
			'button',
			[
				'href'           => $link,
				'class'          => $button_class,
				'data-bs-toggle' => 'video',
			]
		);

		$image_class = $settings['image_class'];
		$image_src   = $settings['image']['url'];

		$parent->add_render_attribute(
			'image_attribute',
			[
				'class' => $image_class,
				'src'   => $image_src,
				'alt'   => Control_Media::get_image_alt( $settings['image'] ),
			]
		);

		$play_button_video_title_class = [];

		if ( $skin_settings['play_button_video_title_class'] ) {
			$play_button_video_title_class[] = $skin_settings['play_button_video_title_class'];
		}

		$parent->add_render_attribute(
			'video_title_attribute',
			[
				'class' => $play_button_video_title_class,

			]
		);

		$play_button_video_desc_class = [];

		if ( $skin_settings['play_button_video_desc_class'] ) {
			$play_button_video_desc_class[] = $skin_settings['play_button_video_desc_class'];
		}

		$parent->add_render_attribute(
			'video_desc_attribute',
			[
				'class' => $play_button_video_desc_class,

			]
		);

		$top_wrapper_class = [];

		if ( $skin_settings['top_wrapper_class'] ) {
			$top_wrapper_class[] = $skin_settings['top_wrapper_class'];
		}

		$parent->add_render_attribute(
			'top_wrapper_class_attribute',
			[
				'class' => $top_wrapper_class,

			]
		);

		$second_wrapper_class = [];

		if ( $skin_settings['second_wrapper_class'] ) {
			$second_wrapper_class[] = $skin_settings['second_wrapper_class'];
		}

		$parent->add_render_attribute(
			'second_wrapper_class_attribute',
			[
				'class' => $second_wrapper_class,

			]
		);

		$span_class = [];

		if ( $skin_settings['span_class'] ) {
			$span_class[] = $skin_settings['span_class'];
		}

		$span_style = [];

		if ( $skin_settings['span_style'] ) {
			$span_style[] = $skin_settings['span_style'];
		}

		$parent->add_render_attribute(
			'span_class_attribute',
			[
				'class' => $span_class,
				'style' => $span_style,

			]
		);

		?><div  <?php $parent->print_render_attribute_string( 'top_wrapper_class_attribute' ); ?>>
			<?php if ( 'yes' === $skin_settings['play_button'] ) : ?>
			<div  <?php $parent->print_render_attribute_string( 'second_wrapper_class_attribute' ); ?>>
				<a <?php $parent->print_render_attribute_string( 'button' ); ?>>
					<?php
					if ( ! isset( $skin_settings['selected_icon']['value']['url'] ) ) {
						?>
						<i class="<?php echo esc_attr( $skin_settings['selected_icon']['value'] ); ?> sn-button-icon" aria-hidden="true"></i>
						<?php
					}
					if ( isset( $skin_settings['selected_icon']['value']['url'] ) ) {
						Icons_Manager::render_icon( $skin_settings['selected_icon'] );
					}
					?>
				</a>
				<?php if ( 'yes' === $skin_settings['play_button_desc_enable'] ) : ?>
					<?php if ( ! empty( $skin_settings['play_button_video_title'] ) ) : ?>
						<div <?php $parent->print_render_attribute_string( 'video_title_attribute' ); ?>><?php echo esc_html( $skin_settings['play_button_video_title'] ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $skin_settings['play_button_video_desc'] ) ) : ?>
						<div <?php $parent->print_render_attribute_string( 'video_desc_attribute' ); ?>><?php echo esc_html( $skin_settings['play_button_video_desc'] ); ?></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<span <?php $parent->print_render_attribute_string( 'span_class_attribute' ); ?>></span>
			<?php
			$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' ) );
			echo wp_kses_post( $this->add_class_to_image_html( $image_html, $image_class ) );
			?>
		</div>
		<?php
	}
	/**
	 * Render the Image class and size..
	 *
	 * @param string $image_html image_html arguments.
	 * @param array  $img_classes attributes.
	 * @return string
	 */
	public function add_class_to_image_html( $image_html, $img_classes ) {
		if ( is_array( $img_classes ) ) {
			$str_class = implode( ' ', $img_classes );
		} else {
			$str_class = $img_classes;
		}

		if ( false === strpos( $image_html, 'class="' ) ) {
			$image_html = str_replace( '<img', '<img class="' . esc_attr( $str_class ) . '"', $image_html );
		} else {
			$image_html = str_replace( 'class="', 'class="' . esc_attr( $str_class ) . ' ', $image_html );
		}

		return $image_html;
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $widget widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $widget ) {
		if ( 'image' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}
}
