<?php
namespace SiliconElementor\Modules\CategoriesDropdown\Widgets;

use Elementor\Controls_Manager;
use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Ticket Card Widget
 */
class Categories_Dropdown extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'si-category';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Categories Dropdown', 'silicon-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'category' ];
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Arguments', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'class',
			[
				'label'       => esc_html__( 'Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'form-select',
				'description' => esc_html( "Value for the 'class' attribute of the select element" ),
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'jetpack-portfolio-type',
				'description' => esc_html( 'Enter the name of the taxonomy or taxonomies to retrieve' ),
			]
		);

		$this->add_control(
			'cat_dropdown_id',
			[
				'label'       => esc_html__( 'ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'portfolio-dropdown',
				'description' => esc_html__( "Value for the 'id' attribute of the select element", 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'name',
			[
				'label'       => esc_html__( 'Name', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'jetpack-portfolio-type',
				'description' => esc_html__( "Value for the 'class' attribute of the select element", 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_option_all',
			[
				'label'       => esc_html__( 'Show Option All', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'All Categories', 'silicon-elementor' ),
				'description' => esc_html__( 'Text to display for showing all categories.', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

	}


	/**
	 * Render Ticket card widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<?php
			wp_dropdown_categories(
				array(
					'class'           => $settings['class'],
					'taxonomy'        => $settings['taxonomy'],
					'id'              => $settings['cat_dropdown_id'],
					'name'            => $settings['name'],
					'show_option_all' => $settings['show_option_all'],
					'value_field'     => 'slug',
				)
			);
			?>
		</form>
		<script>
		/* <![CDATA[ */
		(function() {
			var dropdown = document.getElementById( "<?php echo esc_html( $settings['cat_dropdown_id'] ); ?>" );
			function onCatChange() {
				const selectedCat = dropdown.options[ dropdown.selectedIndex ].value;
				if ( selectedCat >= 0 || selectedCat ) {
					dropdown.parentNode.submit();
				}
			}
			dropdown.onchange = onCatChange;
		})();
		/* ]]> */
		</script>
		<?php
	}
}
