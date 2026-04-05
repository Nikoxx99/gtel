<?php
namespace SiliconElementor\Modules\TeamMember\Skins;

use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Control_Media;
use Elementor\Utils;
use SiliconElementor\Core\Utils as SN_Utils;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Skin Card style.
 */
class Skin_Team_Member extends Skin_Base {
	/**
	 * Get the skin id.
	 */
	public function get_id() {
		return 'sn-team-member';
	}

	/**
	 * Get the skin name.
	 */
	public function get_title() {
		return esc_html__( 'Style 1', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/si-team-member/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		// Remove Features, Badge controls from CONTENT TAB.
		add_action( 'elementor/element/si-team-member/section_general/before_section_end', [ $this, 'update_widget_controls' ], 15 );
		add_action( 'elementor/element/si-team-member/section_general_style/before_section_end', [ $this, 'remove_widget_style_controls' ], 15 );
		add_action( 'elementor/element/si-team-member/section_icon_style/before_section_end', [ $this, 'remove_icon_style_controls' ], 15 );
	}

	/**
	 * Removing Feature section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function update_widget_controls( $widget ) {

		$update_control_ids = [ 'team_member_icons' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => 'sn-team-member',
					],
				]
			);
		}

	}

	/**
	 * Removing Feature section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_icon_style_controls( $widget ) {

		$update_control_ids = [ 'section_icon_style' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-team-member' ],
					],
				]
			);
		}

	}

	/**
	 * Removing Feature section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_widget_style_controls( $widget ) {

		$update_control_ids = [ 'section_general_style', 'section_icon_style', 'icon_normal', 'icon_hover' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-team-member' ],
					],
				]
			);
		}

	}

	/**
	 * Render the skin in the frontend.
	 */
	public function render() {

		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();

		$image_url   = $settings['team_author_image']['url'];
		$image_class = [];
		if ( $settings['team_image_class'] ) {
			$image_class[] = $settings['team_image_class'];
		}

		$title_class    = $settings['title_class'];
		$position_class = $settings['position_class'];

		$widget->add_render_attribute(
			'title_attr',
			[
				'class' => [ $title_class, 'si-elementor__title_name' ],
			]
		);

		$widget->add_render_attribute(
			'byline_attr',
			[
				'class' => [ $position_class, 'si-elementor__position' ],
			]
		);

		$widget->add_render_attribute(
			'image_attr',
			[
				'class' => $image_class,
				'src'   => $image_url,
				'alt'   => Control_Media::get_image_alt( $settings['team_author_image'] ),
				'width' => '48',
			]
		);
		?>
		<div class="d-flex align-items-center">
			<?php
				$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'team_author_image', 'team_author_image' ) );
				echo wp_kses_post( SN_Utils::add_class_to_image_html( $image_html, $image_class ) );
			?>
			<div class="ps-3">
				<h6 <?php $widget->print_render_attribute_string( 'title_attr' ); ?>><?php echo esc_html( $settings['title'] ); ?></h6>
				<p <?php $widget->print_render_attribute_string( 'byline_attr' ); ?>><?php echo esc_html( $settings['position'] ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $widget widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $widget ) {

		if ( 'si-team-member' === $widget->get_name() ) {
			return '';
		}

		return $content;
	}
}
