<?php
namespace SiliconElementor\Modules\Posts\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use SiliconElementor\Base\Base_Widget;
use SiliconElementor\Modules\QueryControl\Module as Module_Query;
use SiliconElementor\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Controls_Manager;
use SiliconElementor\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Portfolio
 */
class Portfolio extends Posts_Base {

	/**
	 * Skip widget.
	 *
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-portfolio';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Portfolio', 'silicon-elementor' );
	}


	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'posts', 'cpt', 'item', 'loop', 'query', 'portfolio', 'custom post type' ];
	}

	/**
	 * Called on import to override.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	/**
	 * Gets Query
	 */
	public function get_query() {
		return $this->_query;
	}


	/**
	 * Register Controls
	 */
	protected function register_controls() {
		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
	}


	/**
	 * Register Query Controls
	 */
	private function register_query_section_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Per Page', 'silicon-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->end_controls_section();

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
				'name'      => 'posts',
				'presets'   => [ 'full' ],
				'exclude'   => [
					'posts_per_page', // use the one from Layout section.
				],
				'post_type' => 'jetpack-portfolio',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Gets Posts per page
	 */
	public function query_posts() {

		$query_args = [
			'posts_per_page' => $this->get_settings( 'posts_per_page' ),
			'paged' => $this->get_current_page(),
		];

		$elementor_query = Module_Query::instance();
		$this->_query    = $elementor_query->get_query( $this, 'posts', $query_args, [] );
	}


	/**
	 * Register the skins for the widget.
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Portfolio_Grid( $this ) );
		$this->add_skin( new Skins\Skin_Portfolio_List( $this ) );
		$this->add_skin( new Skins\Skin_Portfolio_Masonry( $this ) );
	}

}
