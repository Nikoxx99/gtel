<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Grid Card Skin class.
 */
class Skin_Grid_Card extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'grid-card';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Grid Card', 'silicon-elementor' );
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		$query = $this->parent->get_query();
		$index = $query->current_post + 1;
		$total = $query->post_count;

		if ( 1 === $index % 3 ) {
			?>
			<div class="row mb-4 pb-lg-4 pb-3 si-article">
				<div class="col-lg-5 col-12 mb-lg-0 mb-4">
					<?php get_template_part( 'templates/contents/loop-post', 'card-grid' ); ?>
				</div>
				<div class="col gap-y-[1.5rem]">
			<?php
		} else {
			get_template_part( 'templates/contents/loop-post', 'card-list' );
		}

		if ( 3 === $index % 4 || $index === $total ) {
			?>
				</div><!-- /.col -->
			</div><!-- /.row -->
			<?php
		}
	}
}
