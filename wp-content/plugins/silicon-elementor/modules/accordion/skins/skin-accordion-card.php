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
use Elementor\Utils;
use SiliconElementor\Core\Utils as SNUtils;
use Elementor\Control_Media;
use Elementor\Widget_Base;

/**
 * Skin Accordion Silicon
 */
class Skin_Accordion_Card extends Skin_Base {

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
		add_action( 'elementor/element/accordion/section_title/after_section_end', [ $this, 'add_content_controls' ], 10 );
		add_action( 'elementor/element/accordion/section_title/after_section_end', [ $this, 'update_repeater_control' ], 10 );
		add_action( 'elementor/element/accordion/section_toggle_style_content/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'sn-accordion-card';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Card', 'silicon-elementor' );
	}

	/**
	 * Update control of the repeater.
	 */
	public function update_repeater_control() {

		$disable_controls = [
			'tabs',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'sn-accordion-card',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'of' => '_skin',
			]
		);

		$repeater_skin = new Repeater();

		$repeater_skin->add_control(
			'tab_title',
			[
				'label'       => esc_html__( 'Title & Description', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Title', 'silicon-elementor' ),
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater_skin->add_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater_skin->add_control(
			'tab_content',
			[
				'label'      => esc_html__( 'Content', 'silicon-elementor' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => esc_html__( 'Accordion Content', 'silicon-elementor' ),
				'show_label' => false,
			]
		);

		$this->add_control(
			'tabs_cards',
			[
				'label'      => esc_html__( 'Accordion Items', 'silicon-elementor' ),
				'type'       => Controls_Manager::REPEATER,
				'fields'     => $repeater_skin->get_controls(),
				'default'    => $this->get_repeater_defaults(),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => '_skin',
							'operator' => '===',
							'value'    => 'sn-accordion-card',
						],
					],
				],
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Slider defaults.
	 *
	 * @return string
	 */
	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}

	/**
	 * Remove accordion content controls.
	 *
	 * @return void
	 */
	public function remove_content_controls() {

		$disable_controls = [
			'selected_icon',
			'selected_active_icon',
			'faq_schema',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => [ 'sn-accordion-card', 'sn-accordion-silicon' ],
					],
				]
			);
		}
	}

	/**
	 * Add accordion content controls.
	 *
	 * @return void
	 */
	public function add_content_controls() {

		// $this->start_controls_section(
		// 'section_title',
		// [
		// 'label' => esc_html__( 'Additiional Options', 'silicon-elementor' ),
		// 'tab'   => Controls_Manager::TAB_CONTENT,
		// ]
		// );

		// $this->add_control(
		// 'image_class',
		// [
		// 'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
		// 'type'        => Controls_Manager::TEXT,
		// 'title'       => esc_html__( 'Add your custom class for <img> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
		// 'default'     => 'me-md-4 mb-md-0 mb-3',
		// 'label_block' => true,
		// 'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),

		// ]
		// );

		// $this->add_responsive_control(
		// 'width',
		// [
		// 'type'      => Controls_Manager::SLIDER,
		// 'label'     => esc_html__( 'Image Width', 'silicon-elementor' ),
		// 'range'     => [
		// 'px' => [
		// 'min' => 100,
		// 'max' => 1140,
		// ],
		// ],
		// 'default'   => [
		// 'size' => 200,
		// ],
		// 'selectors' => [
		// '{{WRAPPER}} .silicon-accordion img' => 'width: {{SIZE}}{{UNIT}};',
		// ],
		// 'separator' => 'after',
		// ]
		// );

		// $this->end_controls_section();
	}

	/**
	 * Update silicon accordion content controls.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 * @return void
	 */
	public function modifying_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$disable_controls = [
			'section_title_style',
			'section_toggle_style_icon',
			'section_toggle_style_content',
			'section_toggle_style_title',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => [ 'sn-accordion-card', 'sn-accordion-silicon' ],
					],
				]
			);
		}

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

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Image', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <img> tag  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'me-md-4 mb-md-0 mb-3',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the a tag', 'silicon-elementor' ),

			]
		);

		$this->add_responsive_control(
			'width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Image Width', 'silicon-elementor' ),
				'range'     => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
				],
				'default'   => [
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .silicon-accordion img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
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

		$tabs_cards = $this->get_instance_value( 'tabs_cards' );
		$width      = $this->get_instance_value( 'width' );
		$img_class  = $this->get_instance_value( 'image_class' );

		$widget->add_render_attribute( 'accordion_title', 'class', [ 'silicon-accordion-header mb-0' ] );

		$widget->add_render_attribute( 'accordion_content', 'class', [ 'silicon-accordion-body', 'accordion-body', 'silicon-accordion__content', 'pt-0' ] );

		if ( ! empty( $content_css ) ) {
			$widget->add_render_attribute( 'content', 'class', $content_css );
		}

		$id_int = substr( $this->parent->get_id_int(), 0, 3 );
		?>
		<div class="silicon-accordion" id="accordion-<?php echo esc_attr( $id_int ); ?>">
			<?php

			foreach ( $tabs_cards as $index => $item ) :

				$tab_count    = $index + 1;
				$button_class = [ 'silicon-tab-title', 'accordion-button', 'shadow-none', 'rounded-3', 'px-4', 'py-3', 'collapse-accordion-toggle' ];
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

				$widget->add_render_attribute(
					'image-' . $tab_count,
					[
						'class' => $img_class,
						'src'   => $item['image']['url'],
						'width' => $width['size'],
						'alt'   => Control_Media::get_image_alt( $item['image'] ),

					]
				);

				if ( 1 === $tab_count ) {
					$widget->add_render_attribute( 'collapse-' . $tab_count, 'class', 'show' );
				}

				?>
			<div class="accordion-item border-0 rounded-3 shadow-sm mb-3">
				<<?php echo esc_html( $tag ); ?> <?php $widget->print_render_attribute_string( 'accordion_title' ); ?>>
				<button <?php $widget->print_render_attribute_string( 'toggle-' . $tab_count ); ?>>
					<?php echo esc_html( $item['tab_title'] ); ?> 
				</button>
				</<?php echo esc_html( $tag ); ?>>
				<div <?php $widget->print_render_attribute_string( 'collapse-' . $tab_count ); ?>>
					<div <?php $widget->print_render_attribute_string( 'accordion_content' ); ?>>
						<div class="d-flex flex-md-row flex-column align-items-md-center">
							<img <?php $widget->print_render_attribute_string( 'image-' . $tab_count ); ?>>
							<div class="silicon-accordion__content ps-md-3">
							<?php echo wp_kses_post( SNUtils::parse_text_editor( $item['tab_content'], $settings ) ); ?>
							</div>
						</div>
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
