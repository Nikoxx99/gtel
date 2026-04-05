<?php
namespace SiliconElementor\Modules\Parallax\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use SiliconElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use SiliconElementor\Core\Controls_Manager as SI_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Control_Media;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Parallax 3D Tilt Silicon Elementor Widget.
 */
class Parallax_3D_Tilt extends Base_Widget {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-parallax-3d-tilt';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Parallax 3D Tilt', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-parallax';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'image', 'parallax', '3d', 'tilt' ];
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'vanilla-tilt' ];
	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->register_image_controls();

	}

	/**
	 * Register controls for this widget content tab.
	 */
	public function register_image_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Images', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'first_layer',
			[
				'label'   => esc_html__( 'Layer 1 Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'first_layer_thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'second_layer',
			[
				'label'   => esc_html__( 'Layer 2 Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'second_layer_thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Images', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'desktop_margin',
			[
				'label'   => esc_html__( 'Desktop Vertical Spacing', 'silicon-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => -1500,
						'max' => 1500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -300,
				],
			]
		);

		$this->add_control(
			'responsive_margin',
			[
				'label'   => esc_html__( 'Responsive Vertical Spacing', 'silicon-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => -1500,
						'max' => 1500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -150,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Renders the Image Comparison Slider widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="position-relative zindex-5 mx-auto" style="max-width: 1250px;">
		<?php
		$this->add_render_attribute(
			'desktop-margin',
			[
				'class' => 'd-none d-lg-block',
				'style' => 'margin-top: ' . $settings['desktop_margin']['size'] . $settings['desktop_margin']['unit'] . ';',

			]
		);
		$this->add_render_attribute(
			'responsive-margin',
			[
				'class' => 'd-none d-md-block d-lg-none',
				'style' => 'margin-top: ' . $settings['responsive_margin']['size'] . $settings['responsive_margin']['unit'] . ';',

			]
		);
		?>
			<div <?php $this->print_render_attribute_string( 'desktop-margin' ); ?>></div>
			<div <?php $this->print_render_attribute_string( 'responsive-margin' ); ?>></div>

			<!-- Parallax (3D Tilt) gfx -->
			<div class="tilt-3d" data-tilt data-tilt-full-page-listening data-tilt-max="12" data-tilt-perspective="1200">
			<?php
				echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'first_layer_thumbnail', 'first_layer' ) );
			?>
				<div class="tilt-3d-inner position-absolute top-0 start-0 w-100 h-100">
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'second_layer_thumbnail', 'second_layer' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}
}
