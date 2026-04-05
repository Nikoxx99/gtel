<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * List v2 Skin class.
 */
class Skin_Portfolio_Grid extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'grid';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Grid', 'silicon-elementor' );
	}

	/**
	 * Render loop post.
	 *
	 * @param string $row row value.
	 * @param string $column column value.
	 */
	public function render_post( $row, $column ) {

		$column = 2 - ( $column % 2 );
		$total  = $row + $column;

		if ( 0 === $total % 2 ) {
			$width = 'col-md-5';
		} else {
			$width = 'col-md-7';
		}

		$col_class = $width . ' mb-2';
		?><div class="<?php echo esc_attr( $col_class ); ?>">
		<?php
			silicon_get_template( 'templates/portfolio/loop-portfolio-grid.php' );
		?>
		</div>
		<?php

	}

	/**
	 * The base render class. Just include the render_post inside the skin.
	 */
	public function render() {
		$row    = 1;
		$column = 1;
		$this->parent->query_posts();

		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->render_loop_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post( $row, $column );
			if ( 0 === $column % 2 ) {
				$row++;
			}

			$column++;

		}

		wp_reset_postdata();

		$this->render_loop_footer();
	}

	/**
	 * Render Loop Header
	 */
	protected function render_loop_header() {
		$this->parent->add_render_attribute( 'container', array( 'class' => 'row pb-lg-3' ) );
		parent::render_loop_header();
	}
}
