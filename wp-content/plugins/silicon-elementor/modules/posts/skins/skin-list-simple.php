<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * List Simple Skin class.
 */
class Skin_List_Simple extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'list-simple';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'List Simple', 'silicon-elementor' );
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		?>
		<div class="mb-4 si-article">
			<?php get_template_part( 'templates/contents/loop-post', 'list-simple' ); ?>
		</div>
		<?php
	}
}
