<?php
namespace SiliconElementor\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Modules\Carousel\Skins\Skin_Team_Carousel;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Team Carousel
 */
class Team_Carousel extends Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-team-carousel';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Team Carousel', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-carousel';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'carousel', 'team' ];
	}

	/**
	 * Get the group for this widget.
	 *
	 * @return string
	 */
	public function get_group_name() {
		return 'carousel';
	}

	/**
	 * Display Carousel.
	 *
	 * @param array  $slide repeater single control arguments.
	 * @param array  $settings control arguments.
	 * @param string $element_key slider id argument.
	 * @return void
	 */
	protected function print_slide( array $slide, array $settings, $element_key ) {

	}

	/**
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skin_Team_Carousel( $this ) );
	}

	/**
	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $repeater repeater control arguments.
	 * @return void
	 */
	protected function add_repeater_controls( Repeater $repeater ) {

		$repeater->add_control(
			'avatar',
			[
				'label'   => esc_html__( 'Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'large',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'name',
			[
				'label'   => esc_html__( 'Name', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Ralph Edwards', 'silicon-elementor' ),

			]
		);

		$repeater->add_control(
			'role',
			[
				'label'   => esc_html__( 'Role', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Co-Founder', 'silicon-elementor' ),

			]
		);

		$repeater->add_control(
			'link_text',
			[
				'label'       => esc_html__( 'Icon List', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'link|value', 'silicon-elementor' ),
				'default'     => esc_html__( 'https://facebook.com|me-2', 'silicon-elementor' ),
				'description' => esc_html__( 'Enter the social profiles here. Each profile URL should be separated by a new line by hitting Enter button', 'silicon-elementor' ),
			]
		);

	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection(
			[
				'at' => 'before',
				'of' => 'section_slides_style',
			]
		);

		$this->start_controls_section(
			'section_general_style',
			[
				'label'      => esc_html__( 'Card', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
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
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_transparent',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Transparent?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_border',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Hide border?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Hidden', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'condition' => [
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'team_card_hover_background_color',
			[
				'label'     => esc_html__( 'Card BG Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor_top_wrap' => 'background: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'opacity_size',
			[
				'label'     => esc_html__( 'Opacity', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .si-elementor_top_wrap' => 'opacity: {{SIZE}} !important;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label'      => esc_html__( 'Image', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'team_image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for image  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'd-block rounded-circle mx-auto mb-3',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label'      => esc_html__( 'Icon', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .si-team_member__icon_height' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'icon_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'social_icon_color',
			[
				'label'     => esc_html__( 'Social Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color',
			[
				'label'     => esc_html__( 'Social Icon Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'social_icon_border_color',
			[
				'label'     => esc_html__( 'Social Icon Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'selector' =>
					'{{WRAPPER}} .si-team_member__icon',

			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			[
				'label' => esc_html__( 'Hover', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'social_icon_color_hover',
			[
				'label'     => esc_html__( 'Social Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color_hover',
			[
				'label'     => esc_html__( 'Social Icon Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'social_icon_border_color_hover',
			[
				'label'     => esc_html__( 'Social Icon Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label'      => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'fw-medium fs-lg mb-1', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor__title_name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' =>
					'{{WRAPPER}} .si-elementor__title_name',
			]
		);

		$this->add_control(
			'position_class',
			[
				'label'       => esc_html__( 'Role Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'fs-sm mb-3', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'byline_color',
			[
				'label'     => esc_html__( 'Role Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor__position' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'byline_typography',
				'selector' =>
					'{{WRAPPER}} .si-elementor__position',
			]
		);

		$this->end_controls_section();

		$this->end_injection();
	}


	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'avatar' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'avatar' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'avatar' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'avatar' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'avatar' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'avatar' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}
	/**
	 * Get slider settings
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function render_profile_icons( array $slide, array $settings, $element_key ) {

		$silicon_social_icon = $slide['link_text'];

		if ( $silicon_social_icon ) {

			$profiles = explode( "\n", $silicon_social_icon );

			$social_networks = [
				'facebook.com'      => 'facebook',
				'linkedin.com'      => 'linkedin',
				'twitter.com'       => 'twitter',
				'instagram.com'     => 'instagram',
				'github.com'        => 'github',
				'dribbble.com'      => 'dribbble',
				'behance.com'       => 'behance',
				'stackoverflow.com' => 'stack-overflow',

			];

			if ( isset( $profiles ) ) {
				foreach ( $profiles as $social_profile ) {

					if ( empty( trim( $social_profile ) ) ) {
						continue;
					}
					$detail = explode( '|', $social_profile );
					if ( isset( $detail[1] ) ) {
						$class = ' ' . $detail[1];
					}

					$parse = parse_url( $detail[0] );
					$url   = isset( $parse['host'] ) ? $parse['host'] : '';

					if ( isset( $social_networks[ $url ] ) && ! empty( $url ) ) :

						$icon = isset( $class ) ? $social_networks[ $url ] . $class : $social_networks[ $url ];
						?>
					<a href="<?php echo esc_url( $url ); ?>" class="btn btn-icon btn-<?php echo esc_attr( $icon ); ?> btn-outline-secondary btn-sm si-team_member__icon_wrap si-team_member__icon_height">
						<i class="si-team_member__icon bx bxl-<?php echo esc_attr( $social_networks[ $url ] ); ?>"></i>
					</a>
						<?php
					endif;
				}
			}
		}
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
	 * Get slider settings
	 *
	 * @param array $slide the slides settings.
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id.
	 * @return void
	 */
	public function print_team_slide( array $slide, array $settings, $element_key ) {

		$image_url   = $slide['avatar']['url'];
		$image_class = [];
		if ( $settings['team_image_class'] ) {
			$image_class[] = $settings['team_image_class'];
		}

		$card_class = [ 'si-elementor_top_wrap', 'card-body', 'text-center', 'mx-2' ];
		if ( 'yes' === $settings['show_card'] ) {
				$card_class[] = 'card';
		}

		if ( 'yes' === $settings['show_transparent'] ) {
			$card_class[] = 'bg-light';
		}

		if ( 'yes' === $settings['show_border'] ) {
			$card_class[] = 'border-0';
		}

		if ( 'yes' === $settings['show_hover'] ) {
			$card_class[] = 'card-hover';
		}

		$this->add_render_attribute(
			'card_attribute' . $element_key,
			[
				'class' => $card_class,
			]
		);

		$this->add_render_attribute(
			'image_attribute' . $element_key,
			[
				'class' => $image_class,
				'src'   => $image_url,
				'width' => '162',
				'alt'   => Control_Media::get_image_alt( $slide['avatar'] ),
			]
		);

		$this->add_render_attribute(
			'icon_list' . $element_key,
			[
				'class' => [ 'd-flex', 'justify-content-center' ],
			]
		);

		$title_class    = $settings['title_class'];
		$position_class = $settings['position_class'];

		$this->add_render_attribute(
			'title_attribute' . $element_key,
			[
				'class' => [ $title_class, 'si-elementor__title_name' ],
			]
		);

		$this->add_render_attribute(
			'byline_attribute' . $element_key,
			[
				'class' => [ $position_class, 'si-elementor__position' ],
			]
		);

		?>
		<div <?php $this->print_render_attribute_string( 'card_attribute' . $element_key ); ?>>
			<?php
			$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $slide, 'image', 'avatar' ) );
			echo wp_kses_post( $this->add_class_to_image_html( $image_html, $image_class ) );
			?>
			<h5 <?php $this->print_render_attribute_string( 'title_attribute' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></h5>
			<p <?php $this->print_render_attribute_string( 'byline_attribute' . $element_key ); ?>><?php echo esc_html( $slide['role'] ); ?></p>
			<div <?php $this->print_render_attribute_string( 'icon_list' . $element_key ); ?>>
				<?php $this->render_profile_icons( $slide, $settings, $slide['_id'] ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get carousel settings
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings = array(
			'slidesPerView' => 1,
			'spaceBetween'  => isset( $settings['space_between'] ) ? $settings['space_between'] : 0,
		);

		if ( ! empty( $settings['pagination'] ) ) {
			$swiper_settings['pagination']['el'] = '.swiper-pagination';
		}
		if ( 'bullets' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type']      = 'bullets';
			$swiper_settings['pagination']['clickable'] = true;
		}

		if ( 'fraction' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'fraction';
		}
		if ( 'progressbar' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'progressbar';
		}

		if ( 'yes' === $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 8;
			$swiper_settings['breakpoints']['480']['spaceBetween']  = isset( $settings['space_between_mobile'] ) ? $settings['space_between_mobile'] : 8;
			$swiper_settings['breakpoints']['700']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['900']['spaceBetween']  = isset( $settings['space_between_tablet'] ) ? $settings['space_between_tablet'] : 8;
			$swiper_settings['breakpoints']['1160']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
			$swiper_settings['breakpoints']['1500']['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;

		}

		if ( 'slide' === $settings['effect'] ) {
			$swiper_settings['breakpoints']['480']['slidesPerView']  = ! empty( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 2;
			$swiper_settings['breakpoints']['700']['slidesPerView']  = 3;
			$swiper_settings['breakpoints']['900']['slidesPerView']  = ! empty( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 4;
			$swiper_settings['breakpoints']['1160']['slidesPerView'] = 5;
			$swiper_settings['breakpoints']['1500']['slidesPerView'] = ! empty( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 6;

		}
		if ( 'yes' === $settings['show_arrows'] ) {
			$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : 'prev-brand';
			$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : 'next-brand';
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}
		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter'] = true;
		}
		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		return $swiper_settings;
	}
	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		$count = 1;
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings = $this->get_swiper_carousel_options( $settings );

		$this->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( json_encode( $swiper_settings ) ),
			)
		);

		?>
			<?php $this->skin_loop_header( $settings ); ?>
				<?php foreach ( $settings['slides'] as $slide ) : ?>                                    
					<?php
					$this->skin_slide_start( $settings, $slide['_id'] );
					$this->print_team_slide( $slide, $settings, $slide['_id'] );
					$count++;
					?>
					</div>
				<?php endforeach; ?>  
				</div>
			<?php $this->render_swiper_pagination( $settings ); ?>	
			<div class="d-flex">
				<?php
				$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
				$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
				$this->render_arrow_button( $settings, $prev_id, $next_id );
				if ( $settings['show_arrows'] ) {
					$this->add_render_attribute(
						'prev_arrow_button',
						[

							'class' => 'btn btn-prev btn-icon btn-sm me-2',

						]
					);
					$this->add_render_attribute(
						'next_arrow_button',
						[

							'class' => 'btn btn-next btn-icon btn-sm ms-2',

						]
					);
					$this->print_prev_button();
					$this->print_next_button();
				}
				?>
				</div>
			</div>		
		<?php
		$this->render_script();

	}
}
