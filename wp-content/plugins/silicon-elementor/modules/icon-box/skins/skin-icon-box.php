<?php
namespace SiliconElementor\Modules\IconBox\Skins;

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
use Elementor\Widget_Base;

/**
 * Skin IconBox Silicon
 */
class Skin_Icon_Box extends Skin_Base {

	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/icon-box/section_icon/after_section_end', [ $this, 'add_content_control' ], 10 );
		add_action( 'elementor/element/icon-box/section_style_content/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-icon-box';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Style 1', 'silicon-elementor' );
	}

	/**
	 * Added control of the Content tab.
	 */
	public function add_content_control() {

		$disable_controls = [
			'view',
			// 'link',
			'position',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => [ 'si-icon-box', 'si-icon-box-2' ],
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
					$this->get_control_id( 'show_card' ) => 'yes',
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
					$this->get_control_id( 'show_card' ) => 'yes',
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Update control of the style tab.
	 *
	 * @param Elementor\Widget_Base $widget The widget settings.
	 */
	public function modifying_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$disable_controls = [
			'section_style_icon',
			'text_align',
			'content_vertical_alignment',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-icon-box',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'of' => 'section_style_content',
				'at' => 'before',
			]
		);
		$this->start_controls_section(
			'section_style_1_icon',
			[
				'label' => esc_html__( 'Icon', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label'     => esc_html__( 'Backgroud Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon-box-icon' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label'     => esc_html__( 'Padding', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		$this->add_control(
			'icon_space',
			[
				'label'     => esc_html__( 'Spacing', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .sn-elementor-icon-box-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sn-elementor-icon-box-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sn-elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'title_color',
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'of' => 'description_color',
			]
		);

		$this->add_control(
			'description_class',
			[
				'label'       => esc_html__( 'Description Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	public function render() {

		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$skin_control_ids = [
			'show_card',
			'show_border',
			'show_hover',
			'title_class',
			'description_class',
			'icon_class',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$card_class = [ 'si-elementor_card_wrap', 'h-100', 'bg-light' ];
		if ( 'yes' === $skin_settings['show_card'] ) {
				$card_class[] = 'card';
		}

		if ( 'yes' === $skin_settings['show_border'] ) {
			$card_class[] = 'border-0';
		}

		if ( 'yes' === $skin_settings['show_hover'] ) {
			$card_class[] = 'card-hover';
		}

		$parent->add_render_attribute(
			'card_attribute',
			[
				'class' => $card_class,
			]
		);

		$parent->add_link_attributes( 'icon-box-link', $settings['link'] );

		$parent->add_render_attribute( 'title', 'class', [ 'elementor-icon-box-title sn-title', $skin_settings['title_class'] ] );

		$parent->add_render_attribute( 'desc', 'class', [ 'elementor-icon-box-description sn-description', $skin_settings['description_class'] ] );

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$icon_class = $skin_settings['icon_class'];

		?><div <?php $parent->print_render_attribute_string( 'card_attribute' ); ?>>
			<div class="sn-elementor-icon-box-icon bg-secondary rounded-3 w-auto lh-1 p-2 mt-4 ms-4 me-auto">
				<?php
				if ( $settings['selected_icon'] ) {
					Icons_Manager::render_icon(
						$settings['selected_icon'],
						[
							'aria-hidden' => 'true',
							'class'       => [ 'sn-elementor-icon', $icon_class ],
						]
					);
				}
				?>
			</div>
			<div class="card-body">
				<?php if ( ! empty( $settings['link']['url'] ) ) { ?>
					<a <?php $parent->print_render_attribute_string( 'icon-box-link' ); ?>>
				<?php } ?>
				<<?php echo esc_html( $settings['title_size'] ); ?> <?php $parent->print_render_attribute_string( 'title' ); ?>><?php echo esc_html( $settings['title_text'] ); ?></<?php echo esc_html( $settings['title_size'] ); ?>>
				<?php if ( ! empty( $settings['link']['url'] ) ) { ?>
					</a>
				<?php } ?>
				<p <?php $parent->print_render_attribute_string( 'desc' ); ?>><?php echo wp_kses_post( $settings['description_text'] ); ?></p>
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
		if ( 'icon-box' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}
}
