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
class Skin_Nav_Tabs extends Skin_Base {
	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-nav-tabs-skin';
	}
	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Nav Days', 'silicon-elementor' );
	}
	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/si-nav-tabs/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/si-nav-tabs/section_list/before_section_end', [ $this, 'remove_nav_tabs_features_widget_controls' ], 15 );
		add_action( 'elementor/element/si-nav-tabs/section_content_style/before_section_end', [ $this, 'remove_style_nav_tabs_widget_controls' ], 15 );
		add_action( 'elementor/element/si-nav-tabs/section_list_style/after_section_end', [ $this, 'remove_style_list_controls' ], 20 );
	}
	/**
	 * Removing Feature section controls from content tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_nav_tabs_features_widget_controls( $widget ) {
		$update_control_ids = [ 'nav_tabs' ];
		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-nav-tabs-skin', 'si-nav-style-skin' ],
					],
				]
			);
		}
		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'list_vertical',
			[
				'label'       => esc_html__( 'List Item', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item', 'silicon-elementor' ),
				'default'     => esc_html__( 'List Item', 'silicon-elementor' ),
			]
		);
		$repeater->add_control(
			'content_id_vertical',
			[
				'label'       => esc_html__( 'Tab Content ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tab Content Id', 'silicon-elementor' ),
			]
		);
		$repeater->add_control(
			'list_vertical_desc',
			[
				'label'       => esc_html__( 'List Item Description', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'List Item Description', 'silicon-elementor' ),
				'default'     => esc_html__( 'Enter your item description', 'silicon-elementor' ),
			]
		);
		$this->add_control(
			'nav_tabs_vertical',
			[
				'label'       => esc_html__( 'Nav List', 'silicon-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title' => esc_html__( 'Day One', 'silicon-elementor' ),
					],
					[
						'title' => esc_html__( 'Day Two', 'silicon-elementor' ),
					],
				],
				'title_field' => '{{{ list_vertical }}}',
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
		$this->parent       = $widget;
		$update_control_ids = [ 'section_list_style' ];
		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-nav-tabs-skin', 'si-nav-style-skin' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'section_list_style',
			]
		);

		$this->start_controls_section(
			'section_content_skin',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Text Class', 'silicon-elementor' ),
				'separator'   => 'before',
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the title wrap <div> tag', 'silicon-elementor' ),

			]
		);
		$this->add_control(
			'desc_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the desc wrap <div> tag', 'silicon-elementor' ),

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_style',
			[
				'label' => esc_html__( 'List', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_style_tabs' );

		$this->start_controls_tab(
			'tab_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_title_typography',
				'selector' => '{{WRAPPER}} .si-elementor_skin__tab',

			]
		);

		$this->add_control(
			'veritical_list_description',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab_desc' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nav-tabs .nav-link' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover',
			[
				'label' => esc_html__( 'Hover', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'veritical_list_description_hover',
			[
				'label'     => esc_html__( 'Description Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab_desc:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nav-tabs .nav-link:hover' => 'background-color: {{VALUE}} !important;',
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
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-elementor_skin__tab.active' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_background_active_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .si-nav__tab .nav-link.active' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->parent->end_injection();
	}
	/**
	 * Removing Badge section controls from style tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_style_nav_tabs_widget_controls( $widget ) {
		$update_control_ids = [ 'section_content_style' ];
		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-nav-tabs-skin' ],
					],
				]
			);
		}

		$this->parent->end_injection();
	}
	/**
	 * Render the skin in the frontend.
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		$list_id  = uniqid( 'tabs-vertical-' );
		$nav_tabs = $this->get_instance_value( 'nav_tabs_vertical' );
		$widget->add_render_attribute(
			'list_vertical',
			[
				'class'            => [ 'nav', 'nav-tabs', 'flex-nowrap', 'flex-lg-column' ],
				'role'             => 'tablist',
				'aria-orientation' => 'vertical',
			]
		);
		?>
		<div <?php $widget->print_render_attribute_string( 'list_vertical' ); ?>>
		<?php
		foreach ( $nav_tabs as $index => $item ) :
			$count       = $index + 1;
			$active      = '';
			$selected    = 'false';
			$title_class = $this->get_instance_value( 'title_class' );
			$desc_class  = $this->get_instance_value( 'desc_class' );
			if ( 1 === $count ) {
				$active   = 'active';
				$selected = 'true';
				$widget->add_render_attribute( 'list_item_vertical' . $count, 'class', $active );
			}
			$widget->add_render_attribute(
				'list_item_vertical' . $count,
				[
					'href'           => '-#' . $item['content_id_vertical'],
					'class'          => [ 'nav-link', 'd-block', 'w-100', 'rounded-3', 'p-4', 'p-xl-5', 'me-2', 'me-sm-3', 'me-lg-0', 'mb-lg-3', 'si-elementor__tab' ],
					'role'           => 'tab',
					'id'             => $list_id . $count,
					'data-bs-toggle' => 'tab',
					'aria-controls'  => $item['content_id_vertical'],
					'aria-selected'  => $selected,
				]
			);
			$widget->add_render_attribute(
				'list_item_vertical_title' . $count,
				[
					'class' => [ $title_class, 'si-elementor_skin__tab' ],
				]
			);
			$widget->add_render_attribute(
				'list_item_vertical_description' . $count,
				[
					'class' => [ $desc_class, 'si-elementor_skin__tab_desc' ],
				]
			);
			?>
			<a <?php $widget->print_render_attribute_string( 'list_item_vertical' . $count ); ?>>
				<div <?php $widget->print_render_attribute_string( 'list_item_vertical_title' . $count ); ?>><?php echo esc_html( $item['list_vertical'] ); ?></div>
				<div <?php $widget->print_render_attribute_string( 'list_item_vertical_description' . $count ); ?>><?php echo esc_html( $item['list_vertical_desc'] ); ?></div>
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
