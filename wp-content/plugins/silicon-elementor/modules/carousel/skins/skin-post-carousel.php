<?php
namespace SiliconElementor\Modules\Carousel\Skins;

use Elementor;
use Elementor\Skin_Base;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use SiliconElementor\Core\Utils as AR_Utils;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Post Carousel
 */
class Skin_Post_Carousel extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'post-carousel-v1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Carousel v1', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/sn-post-carousel/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/sn-post-carousel/section_slides/before_section_end', [ $this, 'update_section_slides' ], 10, 1 );
		add_action( 'elementor/element/sn-post-carousel/section_additional_options/before_section_end', [ $this, 'update_section_additional_options' ], 10, 1 );
		add_action( 'elementor/element/sn-post-carousel/section_author_style/before_section_end', [ $this, 'update_section_author_style' ], 10, 1 );
		add_action( 'elementor/element/sn-post-carousel/section_navigation/before_section_end', [ $this, 'update_section_navigation' ], 10, 1 );
		add_action( 'elementor/element/sn-post-carousel/section_slides_style/after_section_end', [ $this, 'add_card_css_class_controls' ], 10, 1 );
	}

	/**
	 * Update section slides
	 *
	 * @param array $widget update slides.
	 */
	public function add_card_css_class_controls( $widget ) {
		$this->parent = $widget;
		$this->start_controls_section(
			'section_card_css',
			[
				'label' => __( 'CSS Classes', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'total_wrapper_css',
			[
				'label'       => esc_html__( 'Wrapper CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add your custom class for above swiper in default without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'pb-lg-5 mb-xl-3',
			]
		);

		$this->add_control(
			'default_wrapper_css',
			[
				'label'       => esc_html__( 'Wrapper 2 CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add your custom class for above swiper in default layout without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'px-xl-2',
			]
		);

		$this->add_control(
			'card_css_class',
			[
				'label'       => esc_html__( 'Card CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add your custom class for card-body wrap without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'card h-100 border-0 shadow-sm mx-2',
				'condition' => [
					'display_style!' => 'full_width',
				],
			]
		);

		$this->add_control(
			'card_full_css_class',
			[
				'label'       => esc_html__( 'Card CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add your custom class for card-body wrap without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'card border-0 h-100 mx-1',
				'condition' => [
					'display_style' => 'full_width',
				],
			]
		);

		$this->add_control(
			'card_body_css_class',
			[
				'label'       => esc_html__( 'Card Body CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add your custom class for card-body wrap without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'card-body pb-4',
			]
		);

		$this->add_control(
			'featured_img_css_class',
			[
				'label'       => esc_html__( 'Image CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add your custom class for <img> without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'card-img-top rounded-top',
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
					'default'    => esc_html__( 'Default', 'silicon-elementor' ),
					'full_width' => esc_html__( 'Full Width', 'silicon-elementor' ),
				],
				'condition' => [
					'_skin' => 'post-carousel-v1',
				],
			]
		);
		$this->parent->end_injection();
	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_additional_options( $widget ) {

		$widget->update_control(
			'show_arrows',
			[
				// 'condition' => [
				// 'display_style!' => 'full_width',
				// ],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'display_style',
									'operator' => '!==',
									'value'    => 'full_width',
								],
								[
									'name'     => '_skin',
									'operator' => '===',
									'value'    => 'post-carousel-v1',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => '_skin',
									'operator' => '===',
									'value'    => 'post-carousel-v2',
								],
							],
						],
					],
				],
			]
		);

		$widget->update_control(
			'prev_arrow_id',
			[
				'default'    => 'prev-post',
				// 'condition' => [
				// 'display_style!' => 'full_width',
				// 'show_arrows'    => 'yes',
				// ],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'display_style',
									'operator' => '!==',
									'value'    => 'full_width',
								],
								[
									'name'     => '_skin',
									'operator' => '===',
									'value'    => 'post-carousel-v1',
								],
								[
									'name'     => 'show_arrows',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => '_skin',
									'operator' => '===',
									'value'    => 'post-carousel-v2',
								],
								[
									'name'     => 'show_arrows',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$widget->update_control(
			'next_arrow_id',
			[
				'default'    => 'next-post',
				// 'condition' => [
				// 'display_style!' => 'full_width',
				// 'show_arrows'    => 'yes',
				// ],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'display_style',
									'operator' => '!==',
									'value'    => 'full_width',
								],
								[
									'name'     => '_skin',
									'operator' => '===',
									'value'    => 'post-carousel-v1',
								],
								[
									'name'     => 'show_arrows',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => '_skin',
									'operator' => '===',
									'value'    => 'post-carousel-v2',
								],
								[
									'name'     => 'show_arrows',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);
	}

	/**
	 * Update section additional options
	 *
	 * @param array $widget section additional options.
	 */
	public function update_section_author_style( $widget ) {

		$controls = array( 'desc_heading', 'desc_color', 'author_desc_typography_typography', 'desc_css' );

		foreach ( $controls as $control ) {

			$widget->update_control(
				$control,
				[
					'condition' => [
						'display_style' => 'default',
					],
				]
			);
		}
	}

	/**
	 * Update section navigation options
	 *
	 * @param array $widget section navigation options.
	 */
	public function update_section_navigation( $widget ) {

		$controls = array( 'heading_arrows', 'arrows_size', 'arrows_color', 'arrows_hover_color' );

		foreach ( $controls as $control ) {

			$widget->update_control(
				$control,
				[
					'conditions' => [
						'relation' => 'or',
						'terms'    => [
							[
								'terms' => [
									[
										'name'     => 'display_style',
										'operator' => '!==',
										'value'    => 'full_width',
									],
									[
										'name'     => '_skin',
										'operator' => '===',
										'value'    => 'post-carousel-v1',
									],
									[
										'name'     => 'show_arrows',
										'operator' => '===',
										'value'    => 'yes',
									],
								],
							],
							[
								'terms' => [
									[
										'name'     => '_skin',
										'operator' => '===',
										'value'    => 'post-carousel-v2',
									],
									[
										'name'     => 'show_arrows',
										'operator' => '===',
										'value'    => 'yes',
									],
								],
							],
						],
					],
				]
			);
		}
	}

	/**
	 * Get carousel settings.
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings = null ) {

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$swiper_settings = array();

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

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			$swiper_settings['spaceBetween']                        = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 24;
			$swiper_settings['breakpoints']['1440']['spaceBetween'] = isset( $settings['space_between'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between'] : 24;
			$swiper_settings['breakpoints']['1000']['spaceBetween'] = isset( $settings['space_between_tablet'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_tablet'] : 24;
			$swiper_settings['breakpoints']['500']['spaceBetween']  = isset( $settings['space_between_mobile'] ) && 'yes' === $settings['enable_space_between'] ? $settings['space_between_mobile'] : 24;
		}

		if ( 'fade' === $settings['effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}

		if ( 'full_width' === $settings['display_style'] ) {
			if ( 'slide' === $settings['effect'] ) {
				$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 1;
				$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
				$swiper_settings['breakpoints']['1200']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
				$swiper_settings['breakpoints']['768']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
				$swiper_settings['breakpoints']['576']['slidesPerView']  = 2;
				$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

			}
		}

		if ( 'full_width' !== $settings['display_style'] ) {
			if ( 'slide' === $settings['effect'] ) {
				$swiper_settings['slidesPerView']                        = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;
				$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
				$swiper_settings['breakpoints']['1000']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
				$swiper_settings['breakpoints']['500']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;

			}
		}

		if ( 'full_width' !== $settings['display_style'] ) {
			if ( $settings['show_arrows'] ) {
				$prev_el                       = $settings['prev_arrow_id'] ? $settings['prev_arrow_id'] : '';
				$next_el                       = $settings['next_arrow_id'] ? $settings['next_arrow_id'] : '';
				$swiper_settings['navigation'] = array(
					'prevEl' => '#' . $prev_el,
					'nextEl' => '#' . $next_el,
				);
			}
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
	 * Get slider settings
	 *
	 * @param array $settings The widget settings.
	 * @return void
	 */
	protected function print_slider( array $settings = null ) {
		$widget = $this->parent;
		if ( null === $settings ) {
			$settings = $widget->get_settings_for_display();
		}

		$widget->add_render_attribute(
			'slider',
			array(
				'data-swiper-options' => esc_attr( wp_json_encode( $this->get_swiper_carousel_options( $settings ) ) ),
			)
		);

		$widget->query_posts( $settings );
		$wp_query = $widget->get_query();
		if ( 'full_width' !== $settings['display_style'] ) {
			$widget->render_arrow_button( $settings, 'news-prev', 'news-next' );
			if ( $settings['show_arrows'] ) {
				?>
				<div class="position-relative px-xl-5">
				<?php
			}
			if ( $settings['show_arrows'] ) {
				$widget->add_render_attribute(
					'prev_arrow_button',
					[

						'class' => 'btn btn-prev btn-icon btn-sm position-absolute top-50 start-0 translate-middle-y d-none d-xl-inline-flex',

					]
				);
				$widget->add_render_attribute(
					'next_arrow_button',
					[

						'class' => 'btn btn-next btn-icon btn-sm position-absolute top-50 end-0 translate-middle-y d-none d-xl-inline-flex',

					]
				);
				$widget->print_prev_button();
				$widget->print_next_button();
			}
		}
		if ( 'full_width' === $settings['display_style'] ) :
			$widget->add_render_attribute(
				'total_wrapper__css',
				[
					'class' => $this->get_instance_value( 'total_wrapper_css' ),
				]
			);
			?>
		<div <?php $widget->print_render_attribute_string( 'total_wrapper__css' ); ?>>
			<?php
		elseif ( $settings['show_arrows'] && 'full_width' !== $settings['display_style'] ) :
			$widget->add_render_attribute(
				'default_wrapper__css',
				[
					'class' => $this->get_instance_value( 'default_wrapper_css' ),
				]
			);
			?>
			<div <?php $widget->print_render_attribute_string( 'default_wrapper__css' ); ?>>
				<?php endif; ?>
				<?php
				$widget->skin_loop_header( $settings );
					$element_key = 1;
				while ( $wp_query->have_posts() ) {
					$this->current_permalink = get_permalink();
					$wp_query->the_post();

					$this->print_slide( $settings, $element_key );
					$element_key++;
				}
				wp_reset_postdata();
				?>
					</div>
					<?php
					$widget->render_swiper_pagination( $settings );
					?>
					</div>
		<?php
		if ( 'full_width' === $settings['display_style'] ) {
			?>
			</div>
			<?php
		}
		if ( 'full_width' !== $settings['display_style'] && $settings['show_arrows'] ) :
			?>
			</div>
			</div>
			<?php
		endif;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();

		$this->print_slider( $settings );
		$widget->render_script();

	}

	/**
	 * Print the slide.
	 *
	 * @param array $settings the widget settings.
	 * @param array $element_key the element_key slider id..
	 * @return void
	 */
	public function print_slide( array $settings, $element_key ) {
		$widget                = $this->parent;
		$placeholder_image_src = Utils::get_placeholder_image_src();
		$featured_image        = ! empty( get_the_post_thumbnail_url() ) ? get_the_post_thumbnail_url() : $placeholder_image_src;
		$widget->skin_slide_start( $settings, $element_key );
		$article_css = 'full_width' === $settings['display_style'] ? $this->get_instance_value( 'card_full_css_class' ) : $this->get_instance_value( 'card_css_class' );
		$widget->add_render_attribute( 'sn_article' . $element_key, 'class', $article_css );
		$widget->add_render_attribute( 'card_body_css__class', 'class', $this->get_instance_value( 'card_body_css_class' ) );
		?>
			<article <?php $widget->print_render_attribute_string( 'sn_article' . $element_key ); ?>>
				<div class="position-relative">                     
					<a href="<?php the_permalink(); ?>" class="position-absolute top-0 start-0 w-100 h-100" aria-label="Read more"></a>
					<?php the_post_thumbnail( 'full', array( 'class' => $this->get_instance_value( 'featured_img_css_class' ) ) ); ?>         
				</div>
				<div <?php $widget->print_render_attribute_string( 'card_body_css__class' ); ?>>
				<?php
				$widget->render_meta_data( $settings, $element_key );
				$widget->render_title( $element_key, $settings );
				$widget->add_render_attribute( 'sn_excerpt' . $element_key, 'class', [ 'mb-0', 'custom-excerpt-color', 'silicon-elementor-excerpt__name' ] );
				if ( ! empty( $settings['excerpt_css'] ) ) {
					$widget->add_render_attribute( 'sn_excerpt' . $element_key, 'class', $settings['excerpt_css'] );
				}
				if ( has_excerpt() && $settings['show_excerpt'] ) :
					?>
				<p <?php $widget->print_render_attribute_string( 'sn_excerpt' . $element_key ); ?>><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				</div>
				<?php
				if ( $settings['enable_author'] ) :
					if ( 'default' === $settings['display_style'] && 'yes' === $settings['enable_author_desc'] ) :
						?>
						<div class="card-footer py-4">
							<a href="<?php the_permalink(); ?>" class="d-flex align-items-center text-decoration-none sn-author">
								<?php
								$widget->render_avatar( $settings, $element_key );
								$widget->render_author_info( $settings, $element_key );
								?>
							</a>
						</div>
						<?php
					elseif ( 'default' !== $settings['display_style'] || 'yes' !== $settings['enable_author_desc'] ) :
						?>
						<div class="card-footer py-4">
							<a href="<?php the_permalink(); ?>" class="d-flex align-items-center fw-bold text-dark text-decoration-none">
								<?php
								echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 48, '', '', [ 'class' => 'sn-avatar rounded-circle me-3' ] ) );
								the_author();
								?>
							</a>
						</div>
						<?php
					endif;
				endif;
				?>
			</article>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $post_carousel widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $post_carousel ) {

		if ( 'sn-post-carousel' === $post_carousel->get_name() ) {
			return '';
		}

		return $content;
	}
}
