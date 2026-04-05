<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Utils;
use SiliconElementor\Modules\Posts\Traits\Button_Widget_Trait;
use SiliconElementor\Plugin;
use SiliconElementor\Modules\Posts\Widgets\Posts_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The posts skin base
 */
abstract class Skin_Base extends Elementor_Skin_Base {

	use Button_Widget_Trait;

	/**
	 * Save current permalink to avoid conflict with plugins the filters the permalink during the post render.
	 *
	 * @var string
	 */
	protected $current_permalink;

	/**
	 * Register control actions
	 */
	protected function _register_controls_actions() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_action( 'elementor/element/si-posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
	}

	/**
	 * Register controls for the skin.
	 *
	 * @param Widget_Base $widget The widget instance.
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_post_count_control();
	}

	/**
	 * Register post count control.
	 */
	protected function register_post_count_control() {
		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Per Page', 'silicon-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

	}

	/**
	 * The base render class. Just include the render_post inside the skin.
	 */
	public function render() {
		$this->parent->query_posts();

		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$this->render_loop_header();

		// It's the global `wp_query` it self. and the loop was started from the theme.
		if ( $query->in_the_loop ) {
			$this->current_permalink = get_permalink();
			$this->render_post();
		} else {
			while ( $query->have_posts() ) {
				$query->the_post();

				$this->current_permalink = get_permalink();
				$this->render_post();
			}
		}

		wp_reset_postdata();

		$this->render_loop_footer();
	}

	/**
	 * Get the container class for the loop.
	 */
	public function get_container_class() {
		return 'elementor-posts--skin-' . $this->get_id();
	}

	/**
	 * Render Loop Header
	 */
	protected function render_loop_header() {
		$classes = [
			'elementor-posts-container',
			'elementor-posts',
			$this->get_container_class(),
		];

		$wp_query = $this->parent->get_query();

		$this->parent->add_render_attribute(
			'container',
			[
				'class' => $classes,
			]
		);

		?>
		<div <?php $this->parent->print_render_attribute_string( 'container' ); ?>>
		<?php
	}

	/**
	 * Render message
	 */
	protected function render_message() {
		$settings = $this->parent->get_settings();
		?>
		<div class="e-load-more-message"><?php echo esc_html( $settings['load_more_no_posts_custom_message'] ); ?></div>
		<?php
	}

	/**
	 * Render loop footer
	 */
	public function render_loop_footer() {
		$parent_settings       = $this->parent->get_settings();
		$using_ajax_pagination = in_array(
			$parent_settings['pagination_type'],
			[
				Posts_Base::LOAD_MORE_ON_CLICK,
				Posts_Base::LOAD_MORE_INFINITE_SCROLL,
			],
			true
		);
		?>
		</div>

		<?php if ( $using_ajax_pagination && ! empty( $parent_settings['load_more_spinner']['value'] ) ) : ?>
			<span class="e-load-more-spinner">
				<?php Icons_Manager::render_icon( $parent_settings['load_more_spinner'], [ 'aria-hidden' => 'true' ] ); ?>
			</span>
		<?php endif; ?>

		<?php

		if ( '' === $parent_settings['pagination_type'] ) {
			return;
		}

		$page_limit = $this->parent->get_query()->max_num_pages;

		// Page limit control should not effect in load more mode.
		if ( '' !== $parent_settings['pagination_page_limit'] && ! $using_ajax_pagination ) {
			$page_limit = min( $parent_settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$this->parent->add_render_attribute( 'pagination', 'class', 'elementor-pagination' );

		$has_numbers   = in_array( $parent_settings['pagination_type'], [ 'numbers', 'numbers_and_prev_next' ], true );
		$has_prev_next = in_array( $parent_settings['pagination_type'], [ 'prev_next', 'numbers_and_prev_next' ], true );

		$load_more_type = $parent_settings['pagination_type'];

		$current_page = $this->parent->get_current_page();
		$next_page    = intval( $current_page ) + 1;

		$this->parent->add_render_attribute(
			'load_more_anchor',
			[
				'data-page'      => $current_page,
				'data-max-page'  => $this->parent->get_query()->max_num_pages,
				'data-next-page' => $this->parent->get_wp_link_page( $next_page ),
			]
		);

		?>
		<div class="e-load-more-anchor" <?php $this->parent->print_render_attribute_string( 'load_more_anchor' ); ?>></div>
		<?php

		if ( $using_ajax_pagination ) {
			if ( 'load_more_on_click' === $load_more_type ) {
				// The link-url control is hidden, so default value is added to keep the same style like button widget.
				$this->parent->set_settings( 'link', [ 'url' => '#' ] );

				$this->render_button( $this->parent );
			}

			$this->render_message();
			return;
		}

		$links = [];

		if ( $has_numbers ) {
			$paginate_args = [
				'type'               => 'array',
				'current'            => $this->parent->get_current_page(),
				'total'              => $page_limit,
				'prev_next'          => false,
				'show_all'           => 'yes' !== $parent_settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'silicon-elementor' ) . '</span>',
			];

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$paginate_args          = [];
			$paginate_args['total'] = $page_limit;
			$prev_next = $this->parent->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		if ( ! empty( $parent_settings['navigation_classes'] ) ) {
			$paginate_args['ul_class'] = $parent_settings['navigation_classes'];
		}
		$paginate_args['pagination_type'] =  $parent_settings['pagination_type'];

		echo wp_kses_post( silicon_print_pagination_links( $links, $paginate_args ) );
	}
}
