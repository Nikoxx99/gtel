<?php
namespace SiliconElementor\Modules\TeamMember\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use SiliconElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Control_Media;
use SiliconElementor\Core\Controls_Manager as SI_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use SiliconElementor\Modules\TeamMember\Skins;
use SiliconElementor\Core\Utils as SN_Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Team Member Silicon Elementor Widget.
 */
class Team_Member extends Base_Widget {
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-team-member';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Team Member', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'team', 'member' ];
	}

	/**
	 * Register Skins for pricing widget.
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Team_Member( $this ) );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->team_member_general_controls_content_tab();
		$this->team_member_general_controls_style_tab();

	}

	/**
	 * Register controls for this widget content tab.
	 */
	public function team_member_general_controls_content_tab() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'team_author_image',
			[
				'label'   => esc_html__( 'Choose Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'team_author_image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'team_image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <img> tag', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Name', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Author Name', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'position',
			[
				'label'   => esc_html__( 'Position', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Position', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'enable_rating_icon',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Rating Icon?', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'silicon-elementor' ),
				'condition' => [
					'_skin!' => 'sn-team-member',
				],

			]
		);

		$this->add_control(
			'rating_star',
			[
				'label'     => esc_html__( 'Rating Star', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				],
				'default'   => '5',
				'condition' => [
					'enable_rating_icon' => 'yes',
					'_skin!'             => 'sn-team-member',
				],
			]
		);

		$this->add_control(
			'reviews',
			[
				'label'       => esc_html__( 'Reviews', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Reviews', 'silicon-elementor' ),
				'condition'   => [
					'enable_rating_icon' => 'yes',
					'_skin!'             => 'sn-team-member',
				],
			]
		);

		$default_icon = [
			'value' => 'bx bxl-facebook',
		];

		$repeater = new Repeater();

		$repeater->add_control(
			'selected_item_icon',
			[
				'label'   => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => $default_icon,
			]
		);

		$repeater->add_control(
			'selected_icon_class',
			[
				'label'       => esc_html__( 'Icon Link Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <a> tag', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'icon_link',
			[
				'label'       => esc_html__( 'Icon Link', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
			]
		);

		$this->add_control(
			'team_member_icons',
			[
				'label'     => esc_html__( 'Icon List', 'silicon-elementor' ),
				'type'      => \Elementor\Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for this widget style tab.
	 */
	public function team_member_general_controls_style_tab() {

		$this->start_controls_section(
			'section_general_style',
			[
				'label'      => esc_html__( 'Card', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'show_card',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'show_hover',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Hover', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_transparent',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Transparent?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
				'condition' => [
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_border',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Hide border?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Hidden', 'silicon-elementor' ),
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'condition' => [
					'show_card' => 'yes',
				],
			]
		);

		$this->add_control(
			'team_card_hover_background_color',
			[
				'label'     => esc_html__( 'Card Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor__team-member' => 'background: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'opacity_size',
			[
				'label'     => esc_html__( 'Opacity', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .si-elementor__team-member' => 'opacity: {{SIZE}} !important;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_style',
			[
				'label'      => esc_html__( 'Rating', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'enable_rating_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'rating_icon_size',
			[
				'label'     => esc_html__( 'Rating Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sn-rating-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'rating_icon_color',
			[
				'label'     => esc_html__( 'Rating Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-rating-icon' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'reviews_color',
			[
				'label'     => esc_html__( 'Reviews Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor__reviews' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'reviews_typography',
				'selector' =>
					'{{WRAPPER}} .sn-elementor__reviews',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label'      => esc_html__( 'Icon', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'icon_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'social_icon_color',
			[
				'label'     => esc_html__( 'Social Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color',
			[
				'label'     => esc_html__( 'Social Icon Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'social_icon_border_color',
			[
				'label'     => esc_html__( 'Social Icon Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'selector' =>
					'{{WRAPPER}} .si-team_member__icon',

			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			[
				'label' => esc_html__( 'Hover', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'social_icon_color_hover',
			[
				'label'     => esc_html__( 'Social Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color_hover',
			[
				'label'     => esc_html__( 'Social Icon Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'social_icon_border_color_hover',
			[
				'label'     => esc_html__( 'Social Icon Border Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-team_member__icon_wrap:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label'      => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor__title_name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' =>
					'{{WRAPPER}} .si-elementor__title_name',
			]
		);

		$this->add_control(
			'position_class',
			[
				'label'       => esc_html__( 'Postion Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'byline_color',
			[
				'label'     => esc_html__( 'Byline Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor__position' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'byline_typography',
				'selector' =>
					'{{WRAPPER}} .si-elementor__position',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Icons defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {

		return [
			[
				'selected_item_icon' => [
					'value' => 'bx bxl-facebook',
				],
			],
			[
				'selected_item_icon' => [
					'value' => 'bx bxl-linkedin',
				],
			],
			[
				'selected_item_icon' => [
					'value' => 'bx bxl-twitter',
				],
			],
		];
	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$image_url   = $settings['team_author_image']['url'];
		$image_class = [];
		if ( $settings['team_image_class'] ) {
			$image_class[] = $settings['team_image_class'];
		}

		$card_class = [ 'si-elementor_top_wrap' ];
		if ( 'yes' === $settings['show_card'] ) {
				$card_class[] = 'card';
		}

		if ( 'yes' === $settings['show_transparent'] ) {
			$card_class[] = 'bg-transparent';
		}

		if ( 'yes' === $settings['show_border'] ) {
			$card_class[] = 'border-0';
		}

		if ( 'yes' === $settings['show_hover'] ) {
			$card_class[] = 'card-hover';
		}

		$this->add_render_attribute(
			'card_attribute',
			[
				'class' => $card_class,
			]
		);

		$this->add_render_attribute(
			'image_attribute',
			[
				'class' => $image_class,
				'src'   => $image_url,
				'alt'   => Control_Media::get_image_alt( $settings['team_author_image'] ),
			]
		);

		$this->add_render_attribute(
			'icon_list',
			[
				'class' => [ 'position-relative', 'd-flex', 'zindex-2' ],
			]
		);

		$title_class    = $settings['title_class'];
		$position_class = $settings['position_class'];

		$this->add_render_attribute(
			'title_attribute',
			[
				'class' => [ $title_class, 'si-elementor__title_name' ],
			]
		);

		$this->add_render_attribute(
			'byline_attribute',
			[
				'class' => [ $position_class, 'si-elementor__position' ],
			]
		);

		?><div <?php $this->print_render_attribute_string( 'card_attribute' ); ?>>
			<div class="position-relative">
				<?php
				$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'team_author_image', 'team_author_image' ) );
				echo wp_kses_post( SN_Utils::add_class_to_image_html( $image_html, $image_class ) );
				?>
				<div class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
					<span class="si-elementor__team-member position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-35 rounded-3"></span>
					<div <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
					<?php
					foreach ( $settings['team_member_icons'] as $index => $item ) :
						$count = $index + 1;

						$icon_link  = $item['icon_link'];
						$icon_class = $item['selected_icon_class'];

						$this->add_link_attributes( 'icon_list_item' . $count, $icon_link );

						$this->add_render_attribute(
							'icon_list_item' . $count,
							[
								'class' => [ 'btn', 'btn-icon', 'btn-secondary', 'btn-sm', 'bg-white', 'me-2', $icon_class, 'si-team_member__icon_wrap' ],
							]
						);

						$icon = [ 'si-team_member__icon' ];

						if ( $item['selected_item_icon']['value'] ) {
							$icon[] = $item['selected_item_icon']['value'];
						}

						$this->add_render_attribute(
							'list_icon' . $count,
							[
								'class' => $icon,
							]
						);

						?>
							<a <?php $this->print_render_attribute_string( 'icon_list_item' . $count ); ?>>
								<i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i>
							</a>
						<?php
						endforeach;
					?>
					</div>
				</div>
			</div>
			<div class="card-body text-center p-3">
				<h3 <?php $this->print_render_attribute_string( 'title_attribute' ); ?>><?php echo esc_html( $settings['title'] ); ?></h3>
				<p <?php $this->print_render_attribute_string( 'byline_attribute' ); ?>><?php echo esc_html( $settings['position'] ); ?></p>
				<?php
				if ( 'yes' === $settings['enable_rating_icon'] ) :
					?>
					<div class="d-flex align-items-center justify-content-center">
						<div class="text-nowrap me-1">
							<?php
							for ( $i = 0; $i < 5; $i++ ) {
								$icon_class = 'sn-rating-icon';
								$diff       = $settings['rating_star'] - $i;
								if ( $diff > 0.5 ) {
									$icon_class = $icon_class . ' bx bxs-star text-warning';
								} else {
									$icon_class = $icon_class . ' bx bx-star text-muted opacity-75';
								}
								?>
									<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
								<?php
							}
							?>
						</div>
						<span class="sn-elementor__reviews"><?php echo esc_html( $settings['reviews'] ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

}
