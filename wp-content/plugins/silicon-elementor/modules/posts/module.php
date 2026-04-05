<?php
namespace SiliconElementor\Modules\Posts;

use Elementor\Utils;
use SiliconElementor\Base\Module_Base;
use SiliconElementor\Modules\Posts\Data\Controller;
use SiliconElementor\Modules\Posts\Widgets\Posts_Base;
use SiliconElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Posts module class
 */
class Module extends Module_Base {

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-posts';
	}

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		$widgets = [
			'Posts',
		];
		if ( class_exists( 'Silicon_Jetpack_Portfolio' ) ) {
			$widgets[] = 'Portfolio';
		}

		return $widgets;
	}


	/**
	 * Fix WP 5.5 pagination issue.
	 *
	 * Return true to mark that it's handled and avoid WP to set it as 404.
	 *
	 * @see https://github.com/elementor/elementor/issues/12126
	 * @see https://core.trac.wordpress.org/ticket/50976
	 *
	 * Based on the logic at \WP::handle_404.
	 *
	 * @param bool      $handled - Default false. Is it handled already.
	 * @param \WP_Query $wp_query The custom WP query.
	 *
	 * @return bool
	 */
	public function allow_posts_widget_pagination( $handled, $wp_query ) {
		// Check it's not already handled and it's a single paged query.
		if ( $handled || empty( $wp_query->query_vars['page'] ) || ! is_singular() || empty( $wp_query->post ) ) {
			return $handled;
		}

		$document = Plugin::elementor()->documents->get( $wp_query->post->ID );

		return $this->is_valid_pagination( $document->get_elements_data(), $wp_query->query_vars['page'] );
	}

	/**
	 * Checks a set of elements if there is a posts/archive widget that may be paginated to a specific page number.
	 *
	 * @param array $elements     Array of elements.
	 * @param int   $current_page Current Page ID.
	 *
	 * @return bool
	 */
	public function is_valid_pagination( array $elements, $current_page ) {
		$is_valid = false;

		// Get all widgets that may add pagination.
		$widgets       = Plugin::elementor()->widgets_manager->get_widget_types();
		$posts_widgets = [];
		foreach ( $widgets as $widget ) {
			if ( $widget instanceof Posts_Base ) {
				$posts_widgets[] = $widget->get_name();
			}
		}

		Plugin::elementor()->db->iterate_data(
			$elements,
			function( $element ) use ( &$is_valid, $posts_widgets, $current_page ) {
				if ( isset( $element['widgetType'] ) && in_array( $element['widgetType'], $posts_widgets, true ) ) {
					// Has pagination.
					if ( ! empty( $element['settings']['pagination_type'] ) ) {
						$using_ajax_pagination = in_array(
							$element['settings']['pagination_type'],
							[
								Posts_Base::LOAD_MORE_ON_CLICK,
								Posts_Base::LOAD_MORE_INFINITE_SCROLL,
							],
							true
						);

						// No max pages limits or in load more mode.
						if ( empty( $element['settings']['pagination_page_limit'] ) || $using_ajax_pagination ) {
							$is_valid = true;
						} elseif ( (int) $current_page <= (int) $element['settings']['pagination_page_limit'] ) {
							// Has page limit but current page is less than or equal to max page limit.
							$is_valid = true;
						}
					}
				}
			}
		);

		return $is_valid;
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		Plugin::elementor()->data_manager->register_controller( Controller::class );

		add_filter( 'pre_handle_404', [ $this, 'allow_posts_widget_pagination' ], 10, 2 );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'load-more',
			SILICON_ELEMENTOR_MODULES_URL . '/posts/assets/js/load-more.js',
			[ 'elementor-frontend-modules' ],
			SILICON_ELEMENTOR_VERSION,
			true
		);
	}
}
