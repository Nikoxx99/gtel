<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Grid v2 Skin class.
 */
class Skin_Grid_V2 extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'grid-v2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Grid v2', 'silicon-elementor' );
	}

	/**
	 * Render Loop Header
	 */
	protected function render_loop_header() {
		$this->parent->add_render_attribute( 'container', array( 'class' => 'row row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-md-4 gy-2' ) );
		parent::render_loop_header();
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		?>
		<div class="col pb-3 si-article">
			<?php get_template_part( 'templates/contents/loop-post', 'grid-v2' ); ?>
		</div>
		<?php
	}
}
