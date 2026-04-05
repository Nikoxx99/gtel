<?php
namespace SiliconElementor\Modules\Pricing\Skins;

use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Skin Card style.
 */
class Skin_Card extends Skin_Base {
	/**
	 * Get the skin id.
	 */
	public function get_id() {
		return 'sn-pricing-card';
	}

	/**
	 * Get the skin name.
	 */
	public function get_title() {
		return esc_html__( 'Card', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/si-price-table/print_template', [ $this, 'skin_print_template' ], 10, 2 );

		// Remove Features, Badge controls from CONTENT TAB.
		add_action( 'elementor/element/si-price-table/section_features/before_section_end', [ $this, 'remove_price_features_widget_controls' ], 15 );
		add_action( 'elementor/element/si-price-table/section_badge/before_section_end', [ $this, 'remove_price_badge_widget_controls' ], 15 );

		// Remove Features, Badge controls from STYLE TAB.
		add_action( 'elementor/element/si-price-table/section_header_style/before_section_end', [ $this, 'remove_style_tab_header_widget_controls' ], 15 );
		add_action( 'elementor/element/si-price-table/section_badge_style/before_section_end', [ $this, 'remove_style_tab_price_badge_widget_controls' ], 15 );
		add_action( 'elementor/element/si-price-table/section_features_list_style/before_section_end', [ $this, 'remove_style_tab_price_features_widget_controls' ], 15 );

		// Register card skin controls for CONTENT TAB.
		add_action( 'elementor/element/si-price-table/section_header/before_section_end', [ $this, 'register_price_card_header_controls' ], 15 );
		add_action( 'elementor/element/si-price-table/section_pricing/before_section_end', [ $this, 'register_price_card_price_controls' ], 15 );
		add_action( 'elementor/element/si-price-table/section_pricing_element_style/before_section_end', [ $this, 'remove_style_tab_pricing_widget_controls' ], 15 );
	}

	/**
	 * Render Header section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function register_price_card_header_controls( $widget ) {

		$update_control_ids = [ 'header_icon' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card', 'sn-pricing-list' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'heading_tag',
			]
		);

		$this->add_control(
			'sub_heading',
			[
				'label'   => esc_html__( 'Sub title', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Best for large teams', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Render Pricing section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function register_price_card_price_controls( $widget ) {

		$update_control_ids = [ 'price', 'show_annual', 'monthly_sub_price' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'currency_symbol',
			]
		);

		$this->add_control(
			'card_price',
			[
				'label'   => esc_html__( 'Price', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '50',
				'dynamic' => [
					'active' => true,
				],

			]
		);

		$this->add_control(
			'annually_price',
			[
				'label'   => esc_html__( 'Annually Price', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '10',
				'dynamic' => [
					'active' => true,
				],

			]
		);

		$this->add_control(
			'price_sub_text',
			[
				'label'   => esc_html__( 'Price Sub Text', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'per month',
				'dynamic' => [
					'active' => true,
				],

			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Removing Feature section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_price_features_widget_controls( $widget ) {

		$update_control_ids = [ 'section_features' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card', 'sn-pricing-list' ],
					],
				]
			);
		}
	}

	/**
	 * Removing Feature section controls from style tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_style_tab_price_features_widget_controls( $widget ) {

		$update_control_ids = [ 'section_features_list_style' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card', 'sn-pricing-list' ],
					],
				]
			);
		}
	}

	/**
	 * Removing Badge section controls.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_price_badge_widget_controls( $widget ) {

		$update_control_ids = [ 'section_badge' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card', 'sn-pricing-list' ],
					],
				]
			);
		}
	}


	/**
	 * Removing Badge section controls from style tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_style_tab_price_badge_widget_controls( $widget ) {

		$update_control_ids = [ 'section_badge_style' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card', 'sn-pricing-list' ],
					],
				]
			);
		}
	}

	/**
	 * Removing Header section controls from style tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_style_tab_header_widget_controls( $widget ) {

		$update_control_ids = [ 'wrap_class', 'icon_bg_class', 'transparent_bg', 'shadow-sm' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'heading_class',
			]
		);

		$this->add_control(
			'sub_heading_class',
			[
				'label'     => esc_html__( 'Sub Title Class', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
				'default'   => 'fs-lg',
			]
		);

		$this->add_control(
			'sub_heading_color',
			[
				'label'     => esc_html__( 'Sub Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-price-table__sub_heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_heading_typography',
				'selector' => '{{WRAPPER}} .si-price-table__sub_heading',
			]
		);

		$this->add_control(
			'card_class_section',
			[
				'label'     => esc_html__( 'Card', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'card_bg_class',
			[
				'label' => esc_html__( 'Card Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class for card background. e.g: bg-transparent', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'card_body_class',
			[
				'label' => esc_html__( 'Card Body Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class for card body. e.g: bg-transparent', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'transparent',
			[
				'label'        => esc_html__( 'Enable Transparent', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'enable_border_light',
			[
				'label'        => esc_html__( 'Enable Border Light', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'after',
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Render Pricing section controls from style tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_style_tab_pricing_widget_controls( $widget ) {

		$update_control_ids = [];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-card' ],
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'heading_currency_style',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Price Typography', 'silicon-elementor' ),
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .si-elementor-price-table__integer-part',
			]
		);

		$this->add_control(
			'price_css_class',
			[
				'label'   => esc_html__( 'Price CSS Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'display-5 mb-1',

			]
		);
		$this->add_control(
			'price_annual_css_class',
			[
				'label'   => esc_html__( 'Annual Price CSS Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'display-5 text-dark mb-1',

			]
		);

		$this->add_control(
			'price_sub_text_class',
			[
				'label' => esc_html__( 'Price Sub Text CSS Class', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXT,

			]
		);

		$this->add_control(
			'sub_text_color',
			[
				'label'     => esc_html__( 'Price Sub Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-price-table_price_sub_text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_text_typography',
				'selector' => '{{WRAPPER}} .si-price-table_price_sub_text',
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Render the skin in the frontend.
	 */
	public function render() {
		$settings = $this->parent->get_settings_for_display();

		$symbol = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->parent->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}

		$currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price           = explode( $currency_format, '' );

		$heading_tag = $settings['heading_tag'];

		$skin_control_ids = [
			'sub_heading',
			'sub_heading_class',
			'card_price',
			'price_sub_text',
			'price_sub_text_class',
			'transparent',
			'enable_border_light',
			'card_bg_class',
			'card_body_class',
			'price_css_class',
			'price_annual_css_class',
			'annually_price',

		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$card_bg_class = [ 'card py-3 py-sm-4 py-lg-5' ];
		if ( 'yes' === $skin_settings['transparent'] ) {
			$card_bg_class[] = 'bg-transparent';
		} else {
			$card_bg_class[] = 'bg-light';
		}

		if ( 'yes' === $skin_settings['enable_border_light'] ) {
			$card_bg_class[] = 'border-light';
		} else {
			$card_bg_class[] = 'border-0';
		}

		if ( $skin_settings['card_bg_class'] ) {
			$card_bg_class[] = $skin_settings['card_bg_class'];
		}

		$card_body_text_class = [ 'card-body text-center' ];
		if ( $skin_settings['card_body_class'] ) {
			$card_body_text_class[] = $skin_settings['card_body_class'];
		}

		$sub_heading_class = [ 'pb-4 mb-3 si-price-table__sub_heading' ];
		if ( $skin_settings['sub_heading_class'] ) {
			$sub_heading_class[] = $skin_settings['sub_heading_class'];
		}

		$price_sub_text_class = [ 'mb-5 si-price-table_price_sub_text' ];
		if ( $skin_settings['price_sub_text_class'] ) {
			$price_sub_text_class[] = $skin_settings['price_sub_text_class'];
		}
		$price_css_class = [ 'si-elementor-price-table__integer-part' ];
		if ( ! empty( $skin_settings['price_css_class'] ) ) {
			$price_css_class[] = $skin_settings['price_css_class'];
		}
		$price_annual_css_class = [ 'si-elementor-price-table__integer-part d-none' ];
		if ( ! empty( $skin_settings['price_annual_css_class'] ) ) {
			$price_annual_css_class[] = $skin_settings['price_annual_css_class'];
		}

		$this->parent->add_render_attribute( 'heading', 'class', 'mb-2 si-price-table__heading' );
		$this->parent->add_render_attribute( 'sub_heading_class', 'class', $sub_heading_class );
		$this->parent->add_render_attribute( 'card_bg_class', 'class', $card_bg_class );
		$this->parent->add_render_attribute( 'card_body_class', 'class', $card_body_text_class );
		$this->parent->add_render_attribute( 'price_sub_text_class', 'class', $price_sub_text_class );
		$this->parent->add_render_attribute( 'price_css_class', 'class', $price_css_class );
		$this->parent->add_render_attribute( 'price_annual_css_class', 'class', $price_annual_css_class );
		?>
		<div <?php $this->parent->print_render_attribute_string( 'card_bg_class' ); ?>>
			<div <?php $this->parent->print_render_attribute_string( 'card_body_class' ); ?>>
				<?php if ( ! empty( $settings['heading'] ) ) : ?>
					<<?php echo esc_html( $heading_tag ); ?> <?php $this->parent->print_render_attribute_string( 'heading' ); ?>>
						<?php echo esc_html( $settings['heading'] ); ?>
					</<?php echo esc_html( $heading_tag ); ?>>	
				<?php endif; ?>
				<div <?php $this->parent->print_render_attribute_string( 'sub_heading_class' ); ?>><?php echo esc_html( $skin_settings['sub_heading'] ); ?></div>
				<div <?php $this->parent->print_render_attribute_string( 'price_css_class' ); ?> data-monthly-price>
					<?php
					if ( '' !== $settings['price'] ) :
						$this->parent->render_currency_symbol( $symbol, 'before' );
					endif;

					echo esc_html( trim( $skin_settings['card_price'] ) );

					if ( '' !== $settings['price'] ) :
						$this->parent->render_currency_symbol( $symbol, 'after' );
					endif;
					?>
				</div>
				<div <?php $this->parent->print_render_attribute_string( 'price_annual_css_class' ); ?> data-annual-price>
					<?php
					if ( '' !== $settings['price'] ) :
						$this->parent->render_currency_symbol( $symbol, 'before' );
					endif;

					echo esc_html( trim( $skin_settings['annually_price'] ) );

					if ( '' !== $settings['price'] ) :
						$this->parent->render_currency_symbol( $symbol, 'after' );
					endif;
					?>
				</div>
				<div <?php $this->parent->print_render_attribute_string( 'price_sub_text_class' ); ?>><?php echo esc_html( $skin_settings['price_sub_text'] ); ?></div>
			</div>
			<div class="card-footer border-0 text-center pt-0 pb-4">
				<?php $this->parent->render_footer( $settings ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Skin print template.
	 *
	 * @param array $content the content.
	 * @param array $pricing widget name.
	 * @return string
	 */
	public function skin_print_template( $content, $pricing ) {

		if ( 'si-price-table' === $pricing->get_name() ) {
			return '';
		}

		return $content;
	}
}
