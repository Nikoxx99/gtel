<?php
namespace SiliconElementor\Modules\Slider\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use SiliconElementor\Base\Base_Widget;
use Elementor\Group_Control_Image_Size;
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
 * Image Comparison Slider Silicon Elementor Widget.
 */
class Image_Comparison_Slider extends Base_Widget {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sn-image-comparison-slider';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Image Comparison Slider', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'image', 'comparison', 'slider' ];
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'img-comparison-slider' ];
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
			'image_slot',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Dark Mode Slot', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Slot 2', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Slot 1', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'dark_mode_image',
			[
				'label'   => esc_html__( 'Dark Mode Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'dark_mode_thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'light_mode_image',
			[
				'label'   => esc_html__( 'Light Mode Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'light_mode_thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default'   => 'full',
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
			'left_bg_color',
			[
				'label'     => esc_html__( 'Left Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-dark' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'right_bg_color',
			[
				'label'     => esc_html__( 'Right Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f3f6ff',
				'selectors' => [
					'{{WRAPPER}} .sn-light' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add Attributes to image html.
	 *
	 * @param string $image_html image_html arguments.
	 * @param array  $atts attributes.
	 * @return string
	 */
	public function add_attributes_to_image_html( $image_html, $atts ) {
		foreach ( $atts as $att => $value ) {

			if ( false === strpos( $image_html, "'" . $att . '="' ) ) {
				$image_html = str_replace( '<img', '<img ' . $att . '="' . esc_attr( $value ) . '"', $image_html );
			} else {
				$image_html = str_replace( "'" . $att . '="', "'" . $att . '="' . esc_attr( $value ) . ' ', $image_html );
			}
		}

		return $image_html;
	}

	/**
	 * Renders the Image Comparison Slider widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="d-flex w-100 position-relative overflow-hidden">
			<div class="position-relative flex-xl-shrink-0 zindex-5 start-50 translate-middle-x" style="max-width: 1920px;">
				<div class="mx-md-n5 mx-xl-0">
					<div class="mx-n4 mx-sm-n5 mx-xl-0">
						<div class="mx-n5 mx-xl-0">
							<img-comparison-slider class="mx-n5 mx-xl-0">
							<?php
							if ( 'yes' === $settings['image_slot'] ) {
								$dark_slot = 'first';
							} else {
								$dark_slot = 'second';
							}
							if ( 'yes' !== $settings['image_slot'] ) {
								$light_slot = 'first';
							} else {
								$light_slot = 'second';
							}
							// Dark Mode Image.
							$allowed_html    = array(
								'img' => array(
									'slot'  => array(),
									'src'   => array(),
									'class' => array(),
									'alt'   => array(),
									'title' => array(),
								),
							);
							$dark_image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'dark_mode_thumbnail', 'dark_mode_image' ) );
							$atts            = [ 'slot' => $dark_slot ];
								echo wp_kses( $this->add_attributes_to_image_html( $dark_image_html, $atts ), $allowed_html );
							// Light Mode Image.
							$light_image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'light_mode_thumbnail', 'light_mode_image' ) );
							$atts             = [ 'slot' => $light_slot ];
								echo wp_kses( $this->add_attributes_to_image_html( $light_image_html, $atts ), $allowed_html );
							?>
								<div slot="handle">
								<svg class="text-primary shadow-primary rounded-circle" width="36" height="36" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 36 36"><g><circle fill="currentColor" cx="18" cy="18" r="18"/></g><path fill="#fff" d="M22.2,17.2h-8.3v-3.3L9.7,18l4.2,4.2v-3.3h8.3v3.3l4.2-4.2l-4.2-4.2V17.2z"/></svg>
								</div>
							</img-comparison-slider>
						</div>
					</div>
				</div>
			</div>
			<div class="position-absolute top-0 start-0 w-50 h-100 bg-dark sn-dark"></div>
			<?php
			$this->add_render_attribute(
				'light-bg',
				[
					'class' => 'position-absolute top-0 end-0 w-50 h-100 sn-light',
					'style' => 'background-color:' . esc_html( $settings['right_bg_color'] ) . ';',
				]
			);
			?>
			<div <?php $this->print_render_attribute_string( 'light-bg' ); ?>></div>
		</div>
		<?php
	}
}
