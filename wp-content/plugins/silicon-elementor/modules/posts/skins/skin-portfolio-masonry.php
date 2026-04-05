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
class Skin_Portfolio_Masonry extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'masonry';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Masonry', 'silicon-elementor' );
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		$query     = $this->parent->get_query();
		$index     = $query->current_post + 1;
		$col_class = 'masonry-grid-item col-12';
		if ( 0 === $index % 4 ) {
			$col_class .= ' col-md-8';
		} else {
			$col_class .= ' col-md-4 col-sm-6';
		}
		?><div class="<?php echo esc_attr( $col_class ); ?>">
		<?php
			silicon_get_template( 'templates/portfolio/loop-portfolio-masonry.php' );
		?>
		</div>
		<?php
	}

	/**
	 * Render Loop Header.
	 */
	protected function render_loop_header() {
		$this->parent->add_render_attribute( 'container', array( 'class' => 'masonry-grid row g-md-4 g-3 mb-4' ) );
		parent::render_loop_header();
	}
}
