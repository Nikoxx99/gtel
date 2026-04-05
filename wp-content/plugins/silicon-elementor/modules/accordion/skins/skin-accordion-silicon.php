<?php
namespace SiliconElementor\Modules\Accordion\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use SiliconElementor\Plugin;
use Elementor\Repeater;
use SiliconElementor\Core\Utils as SNUtils;
use Elementor\Widget_Base;

/**
 * Skin Accordion Silicon
 */
class Skin_Accordion_Silicon extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/accordion/section_title/before_section_end', [ $this, 'remove_content_controls' ], 10 );
		add_action( 'elementor/element/accordion/section_toggle_style_content/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'sn-accordion-silicon';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Silicon', 'silicon-elementor' );
	}

	/**
	 * Remove accordion style controls.
	 *
	 * @return void
	 */
	public function remove_content_controls() {

	}


	/**
	 * Update silicon accordion content controls.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 * @return void
	 */
	public function modifying_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->start_controls_section(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( ' Background Hover Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collapsed:hover, {{WRAPPER}} .collapsed:focus' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .collapsed:hover svg path, {{WRAPPER}} .collapsed:focus svg path' => 'fill: {{VALUE}} !important;',
				],

				'separator' => 'after',
			]
		);

		$this->add_control(
			'title_background',
			[
				'label'     => esc_html__( 'Background', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon-accordion .silicon-tab-title' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon-accordion .silicon-tab-title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 's_title_typography',
				'selector' => '{{WRAPPER}} .silicon-accordion .silicon-tab-title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label'     => esc_html__( 'Background', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon-accordion .silicon-accordion__content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .silicon-accordion .silicon-accordion__content' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 's_content_typography',
				'selector' => '{{WRAPPER}} .silicon-accordion .silicon-accordion__content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		$tag      = $settings['title_html_tag'];

		$widget->add_render_attribute( 'accordion_title', 'class', [ 'silicon-accordion-header', 'mb-0' ] );

		$widget->add_render_attribute( 'accordion_content', 'class', [ 'silicon-accordion-body', 'accordion-body', 'px-4', 'py-3', 'silicon-accordion__content' ] );

		if ( ! empty( $content_css ) ) {
			$widget->add_render_attribute( 'content', 'class', $content_css );
		}

		$id_int = substr( $this->parent->get_id_int(), 0, 3 );

		?>
		<div class="silicon-accordion" id="accordion-<?php echo esc_attr( $id_int ); ?>">
			<?php

			foreach ( $settings['tabs'] as $index => $item ) :

				$tab_count    = $index + 1;
				$button_class = [ 'silicon-tab-title', 'accordion-button', 'px-4', 'py-3', 'collapse-accordion-toggle' ];
				if ( 1 !== $tab_count ) {
					$button_class[] = 'collapsed';
				}

				$widget->add_render_attribute(
					'toggle-' . $tab_count,
					[
						'class'          => $button_class,
						'type'           => 'button',
						'data-bs-toggle' => 'collapse',
						'data-bs-target' => '#collapse-' . $id_int . $tab_count,
						'aria-expanded'  => ( 0 === $index ) ? 'true' : 'false',
						'aria-controls'  => 'collapse-' . $id_int . $tab_count,
					]
				);

				$widget->add_render_attribute(
					'collapse-' . $tab_count,
					[
						'class'           => [ 'collapse' ],
						'id'              => 'collapse-' . $id_int . $tab_count,
						'aria-labelledby' => 'collapse-' . $id_int . $tab_count,
						'data-bs-parent'  => '#accordion-' . $id_int,
					]
				);

				if ( 1 === $tab_count ) {
					$widget->add_render_attribute( 'collapse-' . $tab_count, 'class', 'show' );
				}

				?>
			<div class="accordion-item sn-accordion-item ">
				<<?php echo esc_html( $tag ); ?> <?php $widget->print_render_attribute_string( 'accordion_title' ); ?>>
					<button <?php $widget->print_render_attribute_string( 'toggle-' . $tab_count ); ?>>
							<?php echo esc_html( $item['tab_title'] ); ?>
					</button>
				</<?php echo esc_html( $tag ); ?>>
				<div <?php $widget->print_render_attribute_string( 'collapse-' . $tab_count ); ?>>
					<div  <?php $widget->print_render_attribute_string( 'accordion_content' ); ?>>
							<?php echo wp_kses_post( SNUtils::parse_text_editor( $item['tab_content'], $settings ) ); ?>
					</div>
				</div>
			</div>
				<?php
			endforeach;
			?>
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
		if ( 'accordion' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}
}


