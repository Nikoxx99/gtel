<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Hero Skin class.
 */
class Skin_Hero extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'hero';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Hero', 'silicon-elementor' );
	}

	/**
	 * Register controls for the skin.
	 *
	 * @param Widget_Base $widget The widget instance.
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_post_count_control();
		$this->register_hero_layout_control();
	}

	/**
	 * Register hero layout control.
	 */
	public function register_hero_layout_control() {

		$this->add_control(
			'enable_breadcrumb',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Breadcrumb', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'enable_container',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable container', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'feature_text',
			[
				'label'   => esc_html__( 'Feature Title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Hot Topic',
			]
		);

		$this->add_control(
			'feature_image',
			[
				'label' => esc_html__( 'Feature Icon', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,

			]
		);

		$this->add_control(
			'cover_image',
			[
				'label' => esc_html__( 'Cover Image', 'silicon-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

	}


	/**
	 * Render loop header.
	 */
	protected function render_loop_header() {
		$classes = [
			'elementor-posts-container',
			'elementor-posts',
			$this->get_container_class(),
			'position-relative',
			'pb-5',
			'zindex-5',
		];

		$wp_query = $this->parent->get_query();

		if ( 'yes' === $this->get_instance_value( 'enable_container' ) ) {
			$classes[] = 'container';
		}

		$this->parent->add_render_attribute(
			'container',
			[
				'class' => $classes,
			]
		);

		?>
		<section class="dark-mode position-relative jarallax pb-xl-3" data-jarallax data-speed="0.4">
			<?php $this->render_cover_image(); ?>
			<div <?php $this->parent->print_render_attribute_string( 'container' ); ?>>
				<?php $this->render_breadcrumb(); ?>
				<div class="row mb-xxl-5 py-md-4 py-lg-5">
		<?php
	}

	/**
	 * Render cover image.
	 */
	public function render_cover_image() {
		$bg_image = $this->get_instance_value( 'cover_image' )['url'];
		?>
		<!-- Parallax img -->
		<div class="jarallax-img bg-dark opacity-70" style="background-image: url(<?php echo esc_url( $bg_image ); ?>);"></div>
		<!-- Overlay bg -->
		<span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-70 zindex-1"></span>
		<?php
	}

	/**
	 * Render breadcrumb.
	 */
	public function render_breadcrumb() {
		$is_enabled = ( 'yes' === $this->get_instance_value( 'enable_breadcrumb' ) );
		if ( $is_enabled && function_exists( 'silicon_breadcrumb' ) ) {
			$args = array(
				'wrap_before' => '<nav class="py-4" aria-label="breadcrumb"><ol class="breadcrumb mb-0 py-3">',
			);
			silicon_breadcrumb( $args );
		}
	}

	/**
	 * Render loop header.
	 */
	public function render_loop_footer() {
		?>
				</div><!-- /.row -->
			<?php if ( 'yes' === $this->get_instance_value( 'enable_breadcrumb' ) ) { ?>
			</div><!-- /.container -->
			<?php } ?>
		</section>
		<?php
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		$query = $this->parent->get_query();
		$index = $query->current_post + 1;
		$total = $query->post_count;

		if ( 1 === $index ) {
			?>
			<div class="col-lg-6 col-md-7 pb-3 pb-md-0 mb-4 mb-md-5">
				<?php $this->render_featured_post(); ?>
			</div>
			<div class="col-lg-4 offset-lg-2 col-md-5">
			<?php
				$this->render_articles_slider_start();
		} else {
			$this->render_post_slider_article();
		}

		if ( $total === $index ) {
			// This is the last post.
				$this->render_articles_slider_close();
			?>
			</div>
			<?php
		}
	}

	/**
	 * Render the featured post.
	 */
	public function render_featured_post() {

		$feature_text = $this->get_instance_value( 'feature_text' );
		$feature_icon = $this->get_instance_value( 'feature_image' )['url'];

		if ( empty( $feature_icon ) ) {
			$feature_icon = get_template_directory_uri() . '/assets/img/blog/flame.svg';
		}

		?>
		<?php if ( ! empty( $feature_text ) ) : ?>
		<div class="mb-3 fs-lg text-light">
			<?php if ( ! empty( $feature_icon ) ) : ?>
			<img src="<?php echo esc_url( $feature_icon ); ?>" width="24" alt="Flame icon" class="mt-n1 me-1">
			<?php endif; ?>
			<?php echo esc_html( $feature_text ); ?>
		</div>
		<?php endif; ?>
		<?php
		get_template_part( 'templates/contents/loop-post', 'featured' );
	}

	/**
	 * Render the start of articles slider.
	 */
	public function render_articles_slider_start() {
		?>
		<!-- Articles slider -->
		<div class="swiper overflow-hidden w-100 ms-n2 ms-md-0 pe-3 pe-sm-4" style="max-height: 405px;" data-swiper-options='{
			"direction": "vertical",
			"slidesPerView": "auto",
			"freeMode": true,
			"scrollbar": {
				"el": ".swiper-scrollbar"
			},
			"mousewheel": true
		}'>
			<div class="swiper-wrapper pe-md-2">
				<div class="swiper-slide h-auto px-2">
					<div class="row row-cols-md-1 row-cols-sm-2 row-cols-1 g-md-4 g-3">
		<?php
	}

	/**
	 * Render article in the slider.
	 */
	public function render_post_slider_article() {
		?>
		<div class="col">
			<?php get_template_part( 'templates/contents/loop-post', 'slider-article' ); ?>
		</div>
		<?php
	}

	/**
	 * Render the close of articles slider.
	 */
	public function render_articles_slider_close() {
		?>
					</div>
				</div><!-- /.swiper-slide -->
			</div><!-- /.swiper-wrapper -->
			<div class="swiper-scrollbar"></div>
		</div><!-- /.swiper -->
		<?php
	}
}
