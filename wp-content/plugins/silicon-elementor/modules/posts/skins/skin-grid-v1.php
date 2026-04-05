<?php
namespace SiliconElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Grid v1 Skin class.
 */
class Skin_Grid_V1 extends Skin_Base {

	/**
	 * Get the ID of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'grid-v1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Grid v1', 'silicon-elementor' );
	}

	/**
	 * Render loop header.
	 */
	protected function render_loop_header() {
		$this->parent->add_render_attribute( 'container', array( 'class' => 'masonry-grid row row-cols-sm-2 row-cols-1 g-4' ) );
		parent::render_loop_header();
	}

	/**
	 * Render loop post.
	 */
	public function render_post() {
		get_template_part( 'templates/contents/loop-post', 'grid-v1' );
	}
}
