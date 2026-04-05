<?php
namespace SiliconElementor\Modules\Shapes\Widgets;

use SiliconElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shapes Silicon Elementor Widget.
 */
class Shapes extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-shapes';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Shapes', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-shape';
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Shapes', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'svg_select_color',
			[
				'label'   => esc_html__( 'Color', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''          => esc_html__( 'None', 'silicon-elementor' ),
					'primary'   => esc_html__( 'Primary', 'silicon-elementor' ),
					'secondary' => esc_html__( 'Secondary', 'silicon-elementor' ),
					'success'   => esc_html__( 'Success', 'silicon-elementor' ),
					'info'      => esc_html__( 'Info', 'silicon-elementor' ),
					'warning'   => esc_html__( 'Warning', 'silicon-elementor' ),
					'danger'    => esc_html__( 'Danger', 'silicon-elementor' ),
					'light'     => esc_html__( 'Light', 'silicon-elementor' ),
					'dark'      => esc_html__( 'Dark', 'silicon-elementor' ),
				],

			]
		);

		$this->add_control(
			'bg_shapes',
			[
				'label'   => esc_html__( 'Shapes', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg_shape',
				'options' => [
					'bottom_shape' => esc_html__( 'Bottom Shape', 'silicon-elementor' ),
					'bg_shape'     => esc_html__( 'Background Shape', 'silicon-elementor' ),
				],

			]
		);

		$this->add_control(
			'bg_shapes_styles',
			[
				'label'     => esc_html__( 'Styles', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'style_v1',
				'options'   => [
					'style_v1' => esc_html__( 'Style 1', 'silicon-elementor' ),
					'style_v2' => esc_html__( 'Style 2', 'silicon-elementor' ),
				],
				'condition' => [ 'bg_shapes' => 'bg_shape' ],

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Shapes', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'svg_color',
			[
				'label'     => esc_html__( 'SVG Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-shapes' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-shapes' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Bottom Shape render.
	 *
	 * @param string $color svg color.
	 */
	protected function bottom_shape( $color ) {
		$text_color = $color ? ' text-' . $color : '';
		?>
		<!-- Content goes here -->
		<div class="<?php echo esc_attr( $text_color ); ?> sn-shapes" style="color: var(--bs-gray-900);">
			<div class="position-relative start-50 translate-middle-x flex-shrink-0" style="width: 3788px;">
				<svg xmlns="http://www.w3.org/2000/svg" width="3788" height="144" viewBox="0 0 3788 144"><path fill="currentColor" d="M0,0h3788.7c-525,90.2-1181.7,143.9-1894.3,143.9S525,90.2,0,0z"/></svg>
			</div>
		</div>

		<?php
	}

	/**
	 * Background Shape render.
	 *
	 * @param string $color svg color.
	 */
	protected function bg_shape( $color ) {
		$settings     = $this->get_settings_for_display();
		$text_color   = $color ? ' text-' . $color : '';
		$shape_styles = $settings['bg_shapes_styles'];
		?>
		<!-- Content goes here -->
		<div class="<?php echo esc_attr( $text_color ); ?> sn-shapes">
		<?php if ( ( 'style_v1' === $shape_styles ) ) : ?>
			<svg width="469" height="343" viewBox="0 0 469 343" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.08" fill-rule="evenodd" clip-rule="evenodd" d="M273.631 680.872C442.436 768.853 639.315 708.216 717.593 558.212C795.871 408.208 732.941 212.157 564.137 124.176C395.333 36.195 198.453 96.8326 120.175 246.836C41.8972 396.84 104.827 592.891 273.631 680.872ZM236.335 752.344C440.804 858.914 688.289 788.686 789.109 595.486C889.928 402.286 805.903 159.274 601.433 52.7043C396.964 -53.8654 149.479 16.3623 48.6595 209.562C-52.1598 402.762 31.8652 645.774 236.335 752.344Z" fill="currentColor"/><path opacity="0.08" fill-rule="evenodd" clip-rule="evenodd" d="M298.401 633.404C434.98 704.59 598.31 656.971 664.332 530.451C730.355 403.932 675.946 242.827 539.367 171.642C402.787 100.457 239.458 148.076 173.435 274.595C107.413 401.114 161.822 562.219 298.401 633.404ZM288.455 652.464C434.545 728.606 611.369 678.429 683.403 540.391C755.437 402.353 695.402 228.725 549.312 152.583C403.222 76.4404 226.398 126.617 154.365 264.655C82.331 402.693 142.365 576.321 288.455 652.464Z" fill="currentColor"/></svg>
		<?php endif; ?>
		<?php if ( ( 'style_v2' === $shape_styles ) ) : ?>
			<svg width="416" height="444" viewBox="0 0 416 444" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.08" fill-rule="evenodd" clip-rule="evenodd" d="M240.875 615.746C389.471 695.311 562.783 640.474 631.69 504.818C700.597 369.163 645.201 191.864 496.604 112.299C348.007 32.7335 174.696 87.5709 105.789 223.227C36.8815 358.882 92.278 536.18 240.875 615.746ZM208.043 680.381C388.035 776.757 605.894 713.247 694.644 538.527C783.394 363.807 709.428 144.04 529.436 47.6636C349.443 -48.7125 131.584 14.7978 42.8343 189.518C-45.916 364.238 28.0504 584.005 208.043 680.381Z" fill="currentColor"/><path opacity="0.08" fill-rule="evenodd" clip-rule="evenodd" d="M262.68 572.818C382.909 637.194 526.686 594.13 584.805 479.713C642.924 365.295 595.028 219.601 474.799 155.224C354.57 90.8479 210.793 133.912 152.674 248.33C94.5545 362.747 142.45 508.442 262.68 572.818ZM253.924 590.054C382.526 658.913 538.182 613.536 601.593 488.702C665.004 363.867 612.156 206.847 483.554 137.988C354.953 69.129 199.296 114.506 135.886 239.341C72.4752 364.175 125.323 521.195 253.924 590.054Z" fill="currentColor"/></svg>

		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render.
	 */
	protected function render() {

		$settings     = $this->get_settings_for_display();
		$shape        = $settings['bg_shapes'];
		$shape_styles = $settings['bg_shapes_styles'];
		$color        = $settings['svg_select_color'];
		$this->$shape( $color );
	}

}
