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
class Skin_Button_Reviews extends Skin_Base {

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
		return 'button-reviews-silicon';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Reviews', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/button/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/button/section_button/before_section_end', [ $this, 'register_reviews_button_content_controls' ], 15 );
		add_action( 'elementor/element/button/section_style/after_section_end', [ $this, 'register_reviews_button_style_controls' ], 15 );
	}

	/**
	 * Register market button content controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function register_reviews_button_content_controls( $widget ) {

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'   => esc_html__( 'Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Reviewed by',
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'Logo', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'enable_rating_icon',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Rating Icon', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'rating_star',
			[
				'label'     => esc_html__( 'Rating Star', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				],
				'default'   => '5',
				'condition' => [
					$this->get_control_id( 'enable_rating_icon' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'reviews',
			[
				'label'   => esc_html__( 'Reviews', 'silicon-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '49',
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Render reviews button style controls.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 * @return void
	 */
	public function register_reviews_button_style_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->start_controls_section(
			'reviews_section_style',
			[
				'label' => esc_html__( 'Button', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label'     => esc_html__( 'Button', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'   => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#3e4265',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Subtitle Color', 'silicon-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-sub-reviews' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'enable_subtitle_textcase',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable UpperCase', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'image_width',
			[
				'label'      => esc_html__( 'Image Width', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
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
					'unit' => 'px',
					'size' => 94,
				],
				'selectors'  => [
					'{{WRAPPER}} .sn-btn-image' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'rating_icon_size',
			[
				'label'     => esc_html__( 'Rating Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sn-reviews-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'reviews_color',
			[
				'label'     => esc_html__( 'Reviews Color', 'silicon-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-btn-reviews' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Print the stars in star rating.
	 *
	 * @param int   $rating The given rating.
	 * @param array $skin_settings the widget skin_settings.
	 */
	public function print_star_rating( $rating = 5, array $skin_settings = array() ) {
		?>
			<div class="text-nowrap mb-2">
				<?php
				for ( $i = 0; $i < 5; $i++ ) {
					$icon_class = 'sn-reviews-icon';
					$diff       = $rating - $i;
					if ( $diff > 0.5 ) {
						$icon_class = $icon_class . ' bx bxs-star text-warning';
					} else {
						$icon_class = $icon_class . ' bx bx-star text-muted opacity-75';
					}
					?>
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
				<?php } ?>
			</div>
			<?php
	}

	/**
	 * Render Comments.
	 *
	 * @param array $skin_settings the widget skin_settings.
	 * @return void
	 */
	protected function render_reviews( $skin_settings ) {

		$count = $skin_settings['reviews'] ? $skin_settings['reviews'] : 0;
		?>
		   <div class="sn-btn-reviews text-light opacity-70">
		   <?php
			echo esc_html(
				sprintf(
				/* translators: 1: number of comments, 2: post title */
					esc_html( _nx( '%1$s review', '%1$s reviews', $count, 'comments title', 'silicon-elementor' ) ),
					number_format_i18n( $count )
				)
			);
			?>
			</div>
		<?php
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget           = $this->parent;
		$settings         = $this->parent->get_settings_for_display();
		$skin_control_ids = [
			'subtitle',
			'image',
			'image_width',
			'enable_rating_icon',
			'rating_star',
			'rating_icon',
			'reviews',
			'bg_color',
			'button_heading',
			'subtitle_color',
			'enable_subtitle_textcase',
			'reviews_color',

		];
		$skin_settings = [];
		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}
		$bg_color = $skin_settings['bg_color'] ? 'background-color: ' . $skin_settings['bg_color'] . ';' : 'background-color: #3e4265;';
		if ( $settings['button_css_id'] ) {
			$widget->add_render_attribute( 'reviews_button_wrap', 'id', $settings['button_css_id'] );
		}
		$widget->add_link_attributes( 'reviews_button_wrap', $settings['link'] );
		$widget->add_render_attribute(
			'reviews_button_wrap',
			[
				'class' => 'd-inline-flex align-items-center rounded-3 text-decoration-none py-3 px-4',
				'style' => $bg_color,
			]
		);
		?>
		<a <?php $widget->print_render_attribute_string( 'reviews_button_wrap' ); ?>>
			<div class="pe-3">
			<?php
			if ( $skin_settings['subtitle'] ) :
				$subtitle_css  = 'fs-xs text-light opaciy-70 mb-2 sn-sub-reviews';
				$subtitle_css .= 'yes' === $skin_settings['enable_subtitle_textcase'] ? ' text-uppercase' : '';
				$widget->add_render_attribute( 'subtitle', 'class', $subtitle_css );
				?>
			<div <?php $widget->print_render_attribute_string( 'subtitle' ); ?>><?php echo esc_html( $skin_settings['subtitle'] ); ?></div>
			<?php endif; ?>
			<?php
			if ( $skin_settings['image']['url'] ) :
				$widget->add_render_attribute(
					'review_image',
					[
						'src'   => $skin_settings['image']['url'],
						'class' => 'sn-btn-image',
						'alt'   => 'image',
					]
				);
				?>
			<img <?php $widget->print_render_attribute_string( 'review_image' ); ?>>
			<?php endif; ?>
			</div>
			<div class="ps-1">
				<?php
				if ( $skin_settings['enable_rating_icon'] ) {
					$this->print_star_rating( $skin_settings['rating_star'], $skin_settings );
				}
					$this->render_reviews( $skin_settings );
				?>
			</div>
		</a>
		<?php

	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $button widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $button ) {

		if ( 'button' == $button->get_name() ) {
			return '';
		}

		return $content;
	}
}
