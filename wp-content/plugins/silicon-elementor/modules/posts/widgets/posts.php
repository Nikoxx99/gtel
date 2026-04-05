<?php
namespace SiliconElementor\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use SiliconElementor\Modules\QueryControl\Module as Module_Query;
use SiliconElementor\Modules\QueryControl\Controls\Group_Control_Related;
use SiliconElementor\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts Silicon Elementor Widget.
 */
class Posts extends Posts_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-posts';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Posts', 'silicon-elementor' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'silicon', 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
	}

	/**
	 * Called on import to override.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		if ( isset( $element['settings']['posts_post_type'] ) && ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	/**
	 * Register the skins for the widget.
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Grid_V1( $this ) );
		$this->add_skin( new Skins\Skin_Grid_V2( $this ) );
		$this->add_skin( new Skins\Skin_Grid_Card( $this ) );
		$this->add_skin( new Skins\Skin_Hero( $this ) );
		$this->add_skin( new Skins\Skin_List_V1( $this ) );
		$this->add_skin( new Skins\Skin_List_V2( $this ) );
		$this->add_skin( new Skins\Skin_List_Simple( $this ) );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
	}

	/**
	 * Set the query variable
	 */
	public function query_posts() {

		$query_args = [
			'posts_per_page' => $this->get_current_skin()->get_instance_value( 'posts_per_page' ),
			'paged'          => $this->get_current_page(),
		];

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'posts', $query_args, [] );
	}

	/**
	 * Register controls in the Query Section
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name'    => 'posts',
				'presets' => [ 'full' ],
				'exclude' => [
					'posts_per_page', // use the one from Layout section.
				],
			]
		);

		$this->end_controls_section();
	}
}
