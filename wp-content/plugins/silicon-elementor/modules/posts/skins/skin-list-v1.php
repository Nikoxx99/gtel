<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * List v1 Skin class.
 */
class Skin_List_V1 extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'list-v1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'List v1', 'silicon-elementor' );
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		$query = $this->parent->get_query();
		$index = $query->current_post + 1;
		if ( 0 === $index % 2 ) {
			$view = 'even';
		} else {
			$view = 'odd';
		}

		get_template_part( 'templates/contents/loop-post', 'list-v1-' . $view );
	}
}
