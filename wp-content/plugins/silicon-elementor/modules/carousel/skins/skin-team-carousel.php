<?php
namespace SiliconElementor\Modules\Carousel\Skins;

use Elementor;
use Elementor\Skin_Base;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use SiliconElementor\Core\Utils as SN_Utils;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Testimonial Carousel
 */
class Skin_Team_Carousel extends Skin_Base {
	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'team-carousel-1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Team V1', 'silicon-elementor' );
	}



	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_action( 'elementor/element/sn-team-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-team-carousel/section_icon_style/after_section_end', [ $this, 'update_section_add' ], 10, 1 );

	}

	 /**
	  * Update section slides
	  *
	  * @param array $widget update slides.
	  */
	public function update_section_add( $widget ) {
		$this->parent = $widget;

		$this->start_controls_section(
			'section_rating_style',
			[
				'label'      => esc_html__( 'Rating', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
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
					'{{WRAPPER}} .sn-rating-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'rating_icon_color',
			[
				'label'     => esc_html__( 'Rating Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-rating-icon' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'reviews_color',
			[
				'label'     => esc_html__( 'Reviews Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor__reviews' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'reviews_typography',
				'selector' =>
					'{{WRAPPER}} .sn-elementor__reviews',

			]
		);

		$this->end_controls_section();

	}
	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function update_section_slides( $widget ) {
		$this->parent = $widget;
		$widget->update_control(
			'slides',
			[
				'condition' => [
					'_skin!' => 'team-carousel-1',
				],
			]
		);

		$team_repeater = new Repeater();
		$this->add_team_repeater_controls( $team_repeater );
		$widget->start_injection(
			[
				'at' => 'after',
				'of' => 'slides',
			]
		);

		$this->add_control(
			'slides_team',
			[
				'label'   => esc_html__( 'Slides', 'silicon-elementor' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $team_repeater->get_controls(),
				'default' => $this->get_repeater_defaults( $team_repeater ),
			]
		);

		$widget->end_injection();

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
		];
	}

	/**
	/**
	 * Register repeater controls for this widget.
	 *
	 * @param Repeater $team_repeater repeater control arguments.
	 * @return void
	 */
	protected function add_team_repeater_controls( Repeater $team_repeater ) {

		$team_repeater->add_control(
			'avatar',
			[
				'label'   => esc_html__( 'Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$team_repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'large',
				'separator' => 'none',
			]
		);

		$team_repeater->add_control(
			'name',
			[
				'label'   => esc_html__( 'Name', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Ralph Edwards', 'silicon-elementor' ),

			]
		);

		$team_repeater->add_control(
			'role',
			[
				'label'   => esc_html__( 'Role', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Co-Founder', 'silicon-elementor' ),

			]
		);

		$team_repeater->add_control(
			'link_text',
			[
				'label'       => esc_html__( 'Icon List', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'link|value', 'silicon-elementor' ),
				'default'     => esc_html__( 'https://facebook.com|me-2', 'silicon-elementor' ),
				'description' => esc_html__( 'Enter the social profiles here. Each profile URL should be separated by a new line by hitting Enter button', 'silicon-elementor' ),
			]
		);

		$team_repeater->add_control(
			'enable_rating_icon',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Rating Icon?', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'silicon-elementor' ),

			]
		);

		$team_repeater->add_control(
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
					'enable_rating_icon' => 'yes',
				],
			]
		);

		$team_repeater->add_control(
			'reviews',
			[
				'label'       => esc_html__( 'Reviews', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Reviews', 'silicon-elementor' ),
				'condition'   => [
					'enable_rating_icon' => 'yes',
				],
			]
		);

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
					<a href="<?php echo esc_url( $url ); ?>" class="btn btn-icon btn-<?php echo esc_attr( $icon ); ?> btn-secondary btn-sm si-team_member__icon_wrap si-team_member__icon_height">
						<i class="si-team_member__icon bx bxl-<?php echo esc_attr( $social_networks[ $url ] ); ?>"></i>
					</a>
						<?php
					endif;
				}
			}
		}
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
		$widget = $this->parent;

		$image_url   = $slide['avatar']['url'];
		$image_class = [];
		if ( $settings['team_image_class'] ) {
			$image_class[] = $settings['team_image_class'];
		}

		$card_class = [ 'si-elementor_top_wrap' ];
		if ( 'yes' === $settings['show_card'] ) {
				$card_class[] = 'card';
		}

		if ( 'yes' === $settings['show_transparent'] ) {
			$card_class[] = 'bg-transparent';
		}

		if ( 'yes' === $settings['show_border'] ) {
			$card_class[] = 'border-0';
		}

		if ( 'yes' === $settings['show_hover'] ) {
			$card_class[] = 'card-hover';
		}

		$widget->add_render_attribute(
			'card_attribute' . $element_key,
			[
				'class' => $card_class,
			]
		);

		$widget->add_render_attribute(
			'image_attribute' . $element_key,
			[
				'class' => $image_class,
				'src'   => $image_url,
				'width' => '162',
				'alt'   => Control_Media::get_image_alt( $slide['avatar'] ),
			]
		);

		$widget->add_render_attribute(
			'icon_list' . $element_key,
			[
				'class' => [ 'd-flex', 'justify-content-center', 'zindex-2' ],
			]
		);

		$title_class    = $settings['title_class'];
		$position_class = $settings['position_class'];

		$widget->add_render_attribute(
			'title_attribute' . $element_key,
			[
				'class' => [ $title_class, 'si-elementor__title_name' ],
			]
		);

		$widget->add_render_attribute(
			'byline_attribute' . $element_key,
			[
				'class' => [ $position_class, 'si-elementor__position' ],
			]
		);
		$widget = $this->parent;

		$widget->skin_slide_start( $settings, $element_key );
		?>
			<div <?php $widget->print_render_attribute_string( 'card_attribute' . $element_key ); ?>>
				<div class="position-relative">
				<?php
					$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $slide, 'image', 'avatar' ) );
					echo wp_kses_post( $widget->add_class_to_image_html( $image_html, $image_class ) );
				?>
				<div class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
					<span class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-35 rounded-3"></span>
					<div <?php $widget->print_render_attribute_string( 'icon_list' . $element_key ); ?>>
						<?php $this->render_profile_icons( $slide, $settings, $slide['_id'] ); ?>
					</div>
				  </div>
				</div>
				<div class="card-body text-center p-3">
					<h3 <?php $widget->print_render_attribute_string( 'title_attribute' . $element_key ); ?>><?php echo esc_html( $slide['name'] ); ?></h3>
					<p  <?php $widget->print_render_attribute_string( 'byline_attribute' . $element_key ); ?>><?php echo esc_html( $slide['role'] ); ?></p>
					<?php
					if ( 'yes' === $slide['enable_rating_icon'] ) :
						?>
						<div class="d-flex align-items-center justify-content-center">
							<div class="text-nowrap me-1">
								<?php
								for ( $i = 0; $i < 5; $i++ ) {
									$icon_class = 'sn-rating-icon';
									$diff       = $slide['rating_star'] - $i;
									if ( $diff > 0.5 ) {
										$icon_class = $icon_class . ' bx bxs-star text-warning';
									} else {
										$icon_class = $icon_class . ' bx bx-star text-muted opacity-75';
									}
									?>
										<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
									<?php
								}
								?>
							</div>
							<span class="sn-elementor__reviews"><?php echo esc_html( $slide['reviews'] ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget          = $this->parent;
		$settings        = $widget->get_settings_for_display();
		$skin_settings   = $this->get_instance_value( 'slides_team' );
		$swiper_settings = $widget->get_swiper_carousel_options( $settings );
		$count           = 1;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}

		$widget->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( json_encode( $swiper_settings ) ),
			)
		);

		$widget->skin_loop_header( $settings );
		?>
				<?php
				foreach ( $skin_settings as $key => $slide ) :
					$this->print_slide( $slide, $settings, $slide['_id'] );
				endforeach;
				?>
				  
				</div>
			<?php $widget->render_swiper_pagination( $settings ); ?>	
			<div class="d-flex">
				<?php
				$prev_id                       = ! empty( $settings['prev_arrow_id'] ) ? $settings['prev_arrow_id'] : '';
				$next_id                       = ! empty( $settings['next_arrow_id'] ) ? $settings['next_arrow_id'] : '';
				$widget->render_arrow_button( $settings, $prev_id, $next_id );
				if ( $settings['show_arrows'] ) {
					$widget->add_render_attribute(
						'prev_arrow_button',
						[

							'class' => 'btn btn-prev btn-icon btn-sm me-2',

						]
					);
					$widget->add_render_attribute(
						'next_arrow_button',
						[

							'class' => 'btn btn-next btn-icon btn-sm ms-2',

						]
					);
					$widget->print_prev_button();
					$widget->print_next_button();
				}
				?>
				</div>
			</div>		
		<?php
		$widget->render_script();

	}
}
