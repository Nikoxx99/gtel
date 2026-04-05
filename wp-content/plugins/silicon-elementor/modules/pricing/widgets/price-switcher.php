<?php
namespace SiliconElementor\Modules\Pricing\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;

use SiliconElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Pricing Switcher.
 */
class Price_Switcher extends Base_Widget {

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
		return 'si-price-switcher';
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
		return esc_html__( 'Price Switcher', 'silicon-elementor' );
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
		return 'eicon-price-table';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'pricing', 'table', 'switcher' ];
	}

	/**
	 * Pricing Switcher Controls.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_price_switcher',
			[
				'label'              => esc_html__( 'Show Price Switcher ?', 'silicon-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'description'        => esc_html__( 'Enable to display price switcher.', 'silicon-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'price_switcher_id',
			[
				'label'     => esc_html__( 'ID', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'pricing',
				'condition' => [
					'show_price_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_1',
			[
				'label'     => esc_html__( 'Left Label', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Monthly', 'silicon-elementor' ),
				'condition' => [
					'show_price_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_2',
			[
				'label'     => esc_html__( 'Right Label', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Annually', 'silicon-elementor' ),
				'condition' => [
					'show_price_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'percentage_text',
			[
				'label'     => esc_html__( 'Percentage', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( '-10%', 'silicon-elementor' ),
				'condition' => [
					'show_price_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'switcher_css',
			[
				'label'     => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'justify-content-center mt-2 mb-4',
				'condition' => [
					'show_price_switcher' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}


	/**
	 * Render Price Table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$css_classes = array( 'form-check', 'form-switch', 'price-switch' );
		if ( $settings['switcher_css'] ) {
			$css_classes[] = $settings['switcher_css'];
		}
		$this->add_render_attribute(
			'price_switcher_wrap',
			[
				'class'          => $css_classes,
				'data-bs-toggle' => 'price',
			]
		);
		?>
		<?php if ( 'yes' === $settings['show_price_switcher'] ) : ?>

			<div <?php $this->print_render_attribute_string( 'price_switcher_wrap' ); ?>>
				<input id="pricing" type="checkbox" class="form-check-input" data-target="<?php echo esc_attr( $settings['price_switcher_id'] ); ?>">
				<label class="form-check-label" for="pricing"><?php echo esc_attr( $settings['title_1'] ); ?></label>
				<label class="form-check-label d-flex align-items-start" for="pricing"><?php echo esc_attr( $settings['title_2'] ); ?>
					<span class="text-danger fs-xs fw-semibold mt-n2 ms-2"><?php echo esc_html( $settings['percentage_text'] ); ?></span>
				</label>
			</div>
			<?php
		endif;
	}
}
