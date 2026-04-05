<?php

namespace SiliconElementor\Modules\Column;

use SiliconElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The column module class
 */
class Module extends Module_Base {

	/**
	 * Initialize the column module object.
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Return the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-column';
	}

	/**
	 * Add actions to override column element.
	 */
	public function add_actions() {
		add_action( 'elementor/element/column/layout/before_section_start', [ $this, 'add_column_controls' ], 10 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', [ $this, 'add_widget_wrap_controls' ] );
		add_action( 'elementor/element/after_add_attributes', [ $this, 'modify_attributes' ], 20 );
		add_filter( 'elementor/column/print_template', [ $this, 'print_template' ] );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_widget_wrap_controls( $element ) {
		$element->add_control(
			'widget_wrap_heading',
			[
				'label'     => esc_html__( 'Widget Wrapper', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$element->add_control(
			'widget_wrap_max_width',
			[
				'label'      => esc_html__( 'Max Width', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'unit' => 'px' ],
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-widget-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'widget_wrapper_css',
			[
				'label'       => esc_html__( 'CSS Classes', 'silicon-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Applied to elementor-widget-wrap element.', 'silicon-elementor' ),
			]
		);
	}

	/**
	 * Add column controls to the Column Element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_column_controls( $element ) {}

	/**
	 * Modify attributes rendered to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function modify_attributes( $element ) {
		if ( 'column' === $element->get_name() ) {
			$settings = $element->get_settings_for_display();

			if ( ! empty( $settings['widget_wrapper_css'] ) ) {
				$element->add_render_attribute( '_widget_wrapper', 'class', $settings['widget_wrapper_css'] );
			}
		}
	}

	/**
	 * Print the column.
	 */
	public function print_template() {
		ob_start();
		$this->content_template();
		return ob_get_clean();
	}

	/**
	 * The column template.
	 */
	public function content_template() {
		$is_dom_optimization_active = Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		$wrapper_element            = $is_dom_optimization_active ? 'widget' : 'column';

		?>
		<# 
			let wrapper_class = '';

			if( '' != settings.widget_wrapper_css ) {
				wrapper_class = ` ${ settings.widget_wrapper_css }`;
			}

		#>
		<div class="elementor-<?php echo esc_attr( $wrapper_element ); ?>-wrap{{ wrapper_class }}">
			<div class="elementor-background-overlay"></div>
			<?php if ( ! $is_dom_optimization_active ) { ?>
				<div class="elementor-widget-wrap"></div>
			<?php } ?>
		</div>
		<?php
	}
}
