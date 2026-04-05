<?php
namespace SiliconElementor\Modules\NavTabs\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Repeater;
use SiliconElementor\Core\Controls_Manager as SN_Controls_Manager;
use Elementor;

/**
 * Skin Button Silicon
 */
class Skin_Nav_Style_2 extends Skin_Base {
	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-nav-style-skin';
	}
	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Nav Wizard', 'silicon-elementor' );
	}
	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/si-nav-tabs/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/si-nav-tabs/section_list/before_section_end', [ $this, 'remove_nav_tabs_features_widget_controls' ], 20 );
		add_action( 'elementor/element/si-nav-tabs/section_list_style/after_section_end', [ $this, 'remove_style_list_controls' ], 20 );
	}

	/**
	 * Removing Feature section controls from content tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_nav_tabs_features_widget_controls( $widget ) {
		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'list_number_view',
			[
				'label'       => esc_html__( 'List Item No', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item Number', 'silicon-elementor' ),
				'default'     => esc_html__( '1', 'silicon-elementor' ),
			]
		);
		$repeater->add_control(
			'list_view',
			[
				'label'       => esc_html__( 'List Item', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item', 'silicon-elementor' ),
				'default'     => esc_html__( 'List Item', 'silicon-elementor' ),
			]
		);
		$repeater->add_control(
			'content_id_view',
			[
				'label'       => esc_html__( 'Tab Content ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tab Content Id', 'silicon-elementor' ),
			]
		);
		$repeater->add_control(
			'list_view_desc',
			[
				'label'       => esc_html__( 'Description', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'List Item Description', 'silicon-elementor' ),
				'default'     => esc_html__( 'Enter your item description', 'silicon-elementor' ),
			]
		);
		$this->add_control(
			'nav_tabs_view',
			[
				'label'       => esc_html__( 'Nav List', 'silicon-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'list_number_view' => esc_html__( '1', 'silicon-elementor' ),
						'list_view'        => esc_html__( 'Your request*', 'silicon-elementor' ),
						'list_view_desc'   => esc_html__( 'Please choose the type of your request and let us know how we can help.', 'silicon-elementor' ),
					],
					[
						'list_number_view' => esc_html__( '2', 'silicon-elementor' ),
						'list_view'        => esc_html__( 'Personal info*', 'silicon-elementor' ),
						'list_view_desc'   => esc_html__( 'Leave your personal info and we will contact you as soon as possible.', 'silicon-elementor' ),
					],
					[
						'list_number_view' => esc_html__( '3', 'silicon-elementor' ),
						'list_view'        => esc_html__( 'Additional comments', 'silicon-elementor' ),
						'list_view_desc'   => esc_html__( 'If you have any questions or comments please leave us a message. We are here for you!', 'silicon-elementor' ),
					],
				],
				'title_field' => '{{{ list_number_view }}}',
			]
		);

		$this->parent->end_injection();
	}

		/**
		 * Removing list controls from style tab.
		 *
		 * @param Elementor\Widget_Base $widget The Basic gallery widget.
		 * @return void
		 */
	public function remove_style_list_controls( Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'section_list_style',
			]
		);

		$this->start_controls_section(
			'section_content_skin_1',
			[
				'label' => esc_html__( 'List Items Wrap', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Text Class', 'silicon-elementor' ),
				'separator'   => 'before',
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'h5 text-white fw-bold pb-1 mb-2', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title wrap <div> tag', 'silicon-elementor' ),

			]
		);
		$this->add_control(
			'desc_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'text-white opacity-70 mb-0', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the desc wrap <div> tag', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'text_wrapper',
			[
				'label'       => esc_html__( 'Text Wrapper Class', 'silicon-elementor' ),
				'separator'   => 'before',
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'ps-3 ms-1', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title wrap <div> tag', 'silicon-elementor' ),

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'view_section_list_style',
			[
				'label' => esc_html__( 'List', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'view_list_title_typography',
				'selector' => '{{WRAPPER}} .si-elementor_skin__tab',

			]
		);

		$this->add_responsive_control(
			'number_margin',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .si_tabs_number' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'view_tab_style_tabs' );

		$this->start_controls_tab(
			'view_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'view_tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'veritical_view_list_description',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab_desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_number_color',
			[
				'label'     => esc_html__( 'Number Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .si_tabs_number' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_number_color_bg',
			[
				'label'     => esc_html__( 'Number Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0b0f19',
				'selectors' => [
					'{{WRAPPER}} .si_tabs_number' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'text_opacity',
			[
				'label'     => esc_html__( 'Text Opacity', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'default'   => [
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .nav-opacity' => 'opacity : {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active',
			[
				'label' => esc_html__( 'Active', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .active .si-elementor_skin__tab' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_desc_active_color',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .active .si-elementor_skin__tab_desc' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_number_color_active',
			[
				'label'     => esc_html__( 'Number Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .active .si_tabs_number' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_number_color_bg_active',
			[
				'label'     => esc_html__( 'Number Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366F1',
				'selectors' => [
					'{{WRAPPER}} .active .si_tabs_number' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'text_opacity_active',
			[
				'label'     => esc_html__( 'Text Opacity', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'default'   => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .active .nav-opacity' => 'opacity : {{SIZE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->parent->end_injection();
	}

	/**
	 * Render the skin in the frontend.
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		$list_id  = uniqid( 'tabs-style-' );
		$nav_tabs = $this->get_instance_value( 'nav_tabs_view' );
		$widget->add_render_attribute(
			'list_view',
			[
				'class'            => [ $settings['ul_wrap'] ],
				'role'             => 'tablist',
				'aria-orientation' => 'view',
			]
		);
		?>
		<div <?php $widget->print_render_attribute_string( 'list_view' ); ?>>
			<?php
			foreach ( $nav_tabs as $index => $item ) :
				$count        = $index + 1;
				$active       = '';
				$selected     = 'false';
				$title_class  = $this->get_instance_value( 'title_class' );
				$desc_class   = $this->get_instance_value( 'desc_class' );
				$text_wrapper = $this->get_instance_value( 'text_wrapper' );

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$widget->add_render_attribute( 'list_item_view' . $count, 'class', $active );
				}
				$widget->add_render_attribute(
					'list_item_view' . $count,
					[
						'href'           => '-#' . $item['content_id_view'],
						'class'          => [ 'si-elementor__tab', $settings['li_wrap'] ],
						'role'           => 'tab',
						'id'             => $list_id . $count,
						'data-bs-toggle' => 'tab',
						'aria-controls'  => $item['content_id_view'],
						'aria-selected'  => $selected,
					]
				);
				$widget->add_render_attribute(
					'list_item_view_title' . $count,
					[
						'class' => [ $title_class, 'si-elementor_skin__tab' ],
					]
				);

				$widget->add_render_attribute(
					'list_item_view_description' . $count,
					[
						'class' => [ $desc_class, 'si-elementor_skin__tab_desc' ],
					]
				);

				$widget->add_render_attribute(
					'list_item_view_text_wrapper' . $count,
					[
						'class' => [ $text_wrapper, 'nav-opacity' ],
					]
				);

				?>
				<a <?php $widget->print_render_attribute_string( 'list_item_view' . $count ); ?>>
					<div class="btn btn-icon rounded-circle fs-lg fw-bold pe-none si_tabs_number"><?php echo esc_html( $item['list_number_view'] ); ?></div>
					<div <?php $widget->print_render_attribute_string( 'list_item_view_text_wrapper' . $count ); ?>>
						<h4 <?php $widget->print_render_attribute_string( 'list_item_view_title' . $count ); ?>><?php echo esc_html( $item['list_view'] ); ?></h4>
						<?php if ( ! empty( $item['list_view_desc'] ) ) { ?>
							<p <?php $widget->print_render_attribute_string( 'list_item_view_description' . $count ); ?>><?php echo esc_html( $item['list_view_desc'] ); ?></p>
							<?php } ?>
					</div>
				</a>
				<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $sn_tabs widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $sn_tabs ) {
		if ( 'si-nav-tabs' === $sn_tabs->get_name() ) {
			return '';
		}
		return $content;
	}
}
