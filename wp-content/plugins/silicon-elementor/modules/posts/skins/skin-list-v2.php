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
class Skin_List_V2 extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'list-v2';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'List v2', 'silicon-elementor' );
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		get_template_part( 'templates/contents/loop-post', 'list-v2' );
	}
}
