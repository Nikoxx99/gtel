<?php
namespace SiliconElementor\Modules\NavMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use SiliconElementor\Base\Base_Widget;
use SiliconElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Nav-Menu Widget
 */
class Nav_Menu extends Base_Widget {

	/**
	 * Nav Menu Item Index.
	 *
	 * @var array $nav_menu_index index.
	 */
	protected $nav_menu_index = 1;

	/**
	 * Get widget name.
	 *
	 * Retrieve Nav Menu widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'si-nav-menu';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Nav Menu widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Silicon Nav Menu', 'silicon-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Nav Menu widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve Nav Menu widget categories.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget categories.
	 */
	public function get_categories() {
		return [ 'pro-elements', 'silicon-elementor' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve Nav Menu widget keywords.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return [ 'menu', 'nav', 'button' ];
	}

	/**
	 * Get widget depends.
	 *
	 * Retrieve Nav Menu widget script depends.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget depends.
	 */
	public function get_script_depends() {
		return [ 'smartmenus' ];
	}

	/**
	 * Get widget element.
	 *
	 * @param array $element element.
	 */
	public function on_export( $element ) {
		unset( $element['settings']['menu'] );

		return $element;
	}

	/**
	 * Get widget Nav menu index.
	 *
	 * Retrieve Nav Menu widget script Nav menu index.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget Nav menu index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Get widget available menus.
	 *
	 * Retrieve Nav Menu widget script available menus.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget available menus.
	 */
	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Nav Menu', 'silicon-elementor' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'silicon-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					'description'  => sprintf( /* translators: %1$s: Link to Menu Link. */ __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'silicon-elementor' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'silicon-elementor' ) . '</strong><br>' . sprintf( /* translators: %1$s: Link to Menu Link. */__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'silicon-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'view',
			[
				'label'          => esc_html__( 'Layout', 'silicon-elementor' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'traditional',
				'options'        => [
					'traditional' => [
						'title' => esc_html__( 'Default', 'silicon-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					],
					'inline'      => [
						'title' => esc_html__( 'Inline', 'silicon-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wrap_css',
			[
				'label' => __( 'Wrappers', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ul_class',
			[
				'label'   => esc_html__( 'Menu Item Wrap', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'nav',
			]
		);

		$this->add_control(
			'li_class',
			[
				'label'   => esc_html__( 'Menu Item Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'nav-item',
			]
		);

		$this->add_control(
			'anchor_class',
			[
				'label'   => esc_html__( 'Anchor Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'nav-link',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Nav-menu widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		$ul_class = '';
		if ( $settings['ul_class'] ) {
			$ul_class .= ' ' . $settings['ul_class'];
		}

		$li_class = '';
		if ( $settings['li_class'] ) {
			$li_class .= ' ' . $settings['li_class'];
		}

		$anchor_class = '';
		if ( $settings['anchor_class'] ) {
			$anchor_class .= ' ' . $settings['anchor_class'];
		}

		$args = [
			'echo'         => false,
			'menu'         => $settings['menu'],
			'menu_class'   => $ul_class,
			'menu_id'      => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb'  => '__return_empty_string',
			'container'    => '',
			'add_li_class' => $li_class,
			'add_a_class'  => $anchor_class,
		];

		add_filter( 'nav_menu_css_class', [ $this, 'add_additional_class_on_li' ], 1, 3 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'add_additional_class_on_a' ], 1, 3 );

		if ( 'inline' === $settings['view'] ) {
			$args['menu_class'] .= ' flex-column';
		}

		// General Menu.
		$menu_html = wp_nav_menu( $args );

		if ( empty( $menu_html ) ) {
			return;
		}

		?>
		<?php echo wp_kses_post( $menu_html ); ?>
		<?php
	}

	/**
	 * Modify the li classes.
	 *
	 * @param array $classes CSS Class.
	 * @param array $item li item.
	 * @param array $args arguments.
	 */
	public function add_additional_class_on_li( $classes, $item, $args ) {
		if ( isset( $args->add_li_class ) ) {
			$classes[] = $args->add_li_class;
		}
		return $classes;
	}

	/**
	 * Modify the anchor classes.
	 *
	 * @param array $classes CSS Class.
	 * @param array $item li item.
	 * @param array $args arguments.
	 */
	public function add_additional_class_on_a( $classes, $item, $args ) {
		if ( isset( $args->add_a_class ) ) {
			$classes['class'] = $args->add_a_class;
		}
		return $classes;
	}
}
