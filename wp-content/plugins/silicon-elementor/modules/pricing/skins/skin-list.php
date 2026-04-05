<?php
namespace SiliconElementor\Modules\Pricing\Skins;

use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Skin List style.
 */
class Skin_List extends Skin_Base {
	/**
	 * Get the skin id.
	 */
	public function get_id() {
		return 'sn-pricing-list';
	}

	/**
	 * Get the skin name.
	 */
	public function get_title() {
		return esc_html__( 'List', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_filter( 'silicon-elementor/widget/si-price-table/print_template', [ $this, 'skin_print_template' ], 10, 2 );

		// Remove Pricing,Footer controls from CONTENT TAB.
		add_action( 'elementor/element/si-price-table/footer/after_section_end', [ $this, 'remove_widget_footer_section_controls' ], 15 );

		// Remove Pricing,Footer controls from STYLE TAB.
		add_action( 'elementor/element/si-price-table/section_footer_style/before_section_end', [ $this, 'remove_widget_style_tab_footer_section_controls' ], 15 );

		// Register list skin controls for CONTENT & STYLE TAB.
		add_action( 'elementor/element/si-price-table/section_header/before_section_end', [ $this, 'register_skin_list_controls' ], 15 );
		add_action( 'elementor/element/si-price-table/section_header_style/after_section_end', [ $this, 'register_style_tab_skin_list_controls' ], 20 );
	}

	/**
	 * Removing widget footer section controls from content tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_widget_footer_section_controls( $widget ) {

		$update_control_ids = [ 'footer' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-list' ],
					],
				]
			);
		}
	}

	/**
	 * Removing widget footer section controls from style tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_widget_style_tab_footer_section_controls( $widget ) {

		$update_control_ids = [ 'section_footer_style' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-list' ],
					],
				]
			);
		}
	}

	/**
	 * Render skin controls in content tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function register_skin_list_controls( $widget ) {

		$update_control_ids = [ 'heading', 'heading_tag' ];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'sn-pricing-list' ],
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

		$default_icon = [
			'value'   => 'bx bx-check',
			'library' => 'solid',
		];

		$repeater->add_control(
			'icon_item',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'item_icon',
				'default'          => $default_icon,
			]
		);

		$repeater->add_control(
			'title_text',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'List Item', 'silicon-elementor' ),
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'title_text_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .sn-pricing-list-title' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'currency_symbol',
			[
				'label'     => esc_html__( 'Currency Symbol', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''             => esc_html__( 'None', 'silicon-elementor' ),
					'dollar'       => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'silicon-elementor' ),
					'euro'         => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'silicon-elementor' ),
					'baht'         => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'silicon-elementor' ),
					'franc'        => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'silicon-elementor' ),
					'guilder'      => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'silicon-elementor' ),
					'krona'        => 'kr ' . _x( 'Krona', 'Currency Symbol', 'silicon-elementor' ),
					'lira'         => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'silicon-elementor' ),
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'silicon-elementor' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'silicon-elementor' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'silicon-elementor' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency Symbol', 'silicon-elementor' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'silicon-elementor' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'silicon-elementor' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'silicon-elementor' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'silicon-elementor' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'silicon-elementor' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'silicon-elementor' ),
					'custom'       => esc_html__( 'Custom', 'silicon-elementor' ),
				],
				'default'   => 'dollar',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'currency_symbol_custom',
			[
				'label'     => esc_html__( 'Custom Symbol', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'currency_symbol_color',
			[
				'label'     => esc_html__( 'Symbol Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .sn-pricing-list-currency_symbol' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'currency_position',
			[
				'label'   => esc_html__( 'Position', 'silicon-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'silicon-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'after'  => [
						'title' => esc_html__( 'After', 'silicon-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
			]
		);

		$repeater->add_control(
			'ticket_price',
			[
				'label'     => esc_html__( 'Price', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '100',
				'separator' => 'before',
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'price_class',
			[
				'label'   => esc_html__( 'Price Class CSS', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'my-sm-0',
			]
		);

		$repeater->add_control(
			'price_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-pricing-list-price' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$repeater->add_control(
			'list_skin_price_color',
			[
				'label'     => esc_html__( 'Price Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .sn-pricing-list-price' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'date_text',
			[
				'label'     => esc_html__( 'Date', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'until Sep 1, 2021',
				'separator' => 'before',
				'dynamic'   => [
					'active' => true,
				],

			]
		);

		$repeater->add_control(
			'date_class',
			[
				'label'   => esc_html__( 'Date Class', 'silicon-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'fs-sm',
			]
		);

		$repeater->add_control(
			'date_text_color',
			[
				'label'     => esc_html__( 'Date Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .sn-pricing-list-date' => 'color: {{VALUE}}',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .sn-pricing-list-date',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'skin_list',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title_text' => esc_html__( 'Early birds', 'silicon-elementor' ),
						'icon_item'  => $default_icon,
					],
					[
						'title_text' => esc_html__( 'Late birds', 'silicon-elementor' ),
						'icon_item'  => $default_icon,
					],
				],
				'title_field' => '{{{ title_text }}}',
			]
		);

		$this->parent->end_injection();

	}

	/**
	 * Render currency symbol.
	 *
	 * @param array $symbol currency symbol.
	 * @param array $location currency location.
	 * @param array $item currency item.
	 */
	public function render_currency_symbol( $symbol, $location, $item ) {
		$location_setting = ! empty( $item['currency_position'] ) ? $item['currency_position'] : 'before';
		if ( ! empty( $symbol ) && $location === $location_setting ) {
			echo wp_kses_post( '<span class="sn-pricing-list-currency_symbol elementor-currency--' . $location . '">' . $symbol . '</span>' );
		}
	}

	/**
	 * Render skin controls in style tab.
	 *
	 * @param Widget_Base $widget pricing widget.
	 * @return void
	 */
	public function register_style_tab_skin_list_controls( Widget_Base $widget ) {
		$this->parent       = $widget;
		$update_control_ids = [];

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => 'sn-pricing-list',
					],
				]
			);
		}

		$this->start_controls_section(
			'skin_section_header_style',
			[
				'label'      => esc_html__( 'Skin Header', 'silicon-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'skin_heading_style',
			[
				'label'     => esc_html__( 'Title', 'silicon-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .si-price-table__list',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the skin in the frontend.
	 */
	public function render() {
		$settings = $this->parent->get_settings_for_display();

		$skin_control_ids = [
			'icon_item',
			'title_text',
			'currency_symbol',
			'currency_symbol_custom',
			'ticket_price',
			'price_class',
			'date_text',
			'date_class',
			'skin_list',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$migration_allowed = Icons_Manager::is_migration_allowed();
		$count             = 1;

		if ( empty( $skin_settings['skin_list'] ) ) {
			return;
		}
		?>
		<ul class="list-group si-price-table__list">
			<?php
			foreach ( $skin_settings['skin_list'] as $index => $item ) :
				$repeater_setting_key = $this->parent->get_repeater_setting_key( 'title_text', 'skin_list', $index );
				$this->parent->add_inline_editing_attributes( $repeater_setting_key );

				$symbol = '';

				if ( ! empty( $item['currency_symbol'] ) ) {
					if ( 'custom' !== $item['currency_symbol'] ) {
						$symbol = $this->parent->get_currency_symbol( $item['currency_symbol'] );
					} else {
						$symbol = $item['currency_symbol_custom'];
					}
				}

				$migrated = isset( $item['__fa4_migrated']['icon_item'] );
				// add old default.
				if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
					$item['item_icon'] = 'fa fa-check-circle';
				}
				$is_new = ! isset( $item['item_icon'] ) && $migration_allowed;

				if ( count( $skin_settings['skin_list'] ) === $count ) {
					$text_class[1] = 'mb-0';
				}

				$li_class = [ 'list-group-item d-flex flex-column flex-sm-row align-items-center justify-content-between p-4', 'elementor-repeater-item-' . $item['_id'] ];

				$price_class = [ 'sn-pricing-list-price', 'elementor-repeater-item-' . $item['_id'] ];
				if ( $item['price_class'] ) {
					$price_class[] = $item['price_class'];
				}

				$date_class = [ 'sn-pricing-list-date' ];
				if ( $item['date_class'] ) {
					$date_class[] = $item['date_class'];
				}

				$this->parent->add_render_attribute( 'list_item-' . $item['_id'], 'class', $li_class );
				$this->parent->add_render_attribute( 'price_class-' . $item['_id'], 'class', $price_class );
				$this->parent->add_render_attribute( 'date_class', 'class', $date_class );
				?>
				<li <?php $this->parent->print_render_attribute_string( 'list_item-' . $item['_id'] ); ?>>
					<div class="d-flex align-items-center">
						<?php if ( ! empty( $item['item_icon'] ) || ! empty( $item['icon_item'] ) ) : ?>
						<div class="flex-shrink-0 me-2 sn-icon">
							<?php
							if ( $is_new || $migrated ) :
								Icons_Manager::render_icon( $item['icon_item'] );
							else :
								?>
								<i class="<?php echo esc_attr( $item['item_icon'] ); ?>"></i>
								<?php
							endif;
							?>
						</div>
						<?php endif; ?>
						<?php
						if ( ! empty( $item['title_text'] ) ) :
							?>
							<h4 class="fs-base fw-semibold text-nowrap ps-1 mb-0 sn-pricing-list-title">
								<?php echo esc_html( $item['title_text'] ); ?>
							</h4>
							<?php
						else :
							echo '&nbsp;';
						endif;
						?>
					</div>
					<?php
					if ( ! empty( $item['ticket_price'] ) ) :
						?>
						<h5 <?php $this->parent->print_render_attribute_string( 'price_class-' . $item['_id'] ); ?>><?php $this->render_currency_symbol( $symbol, 'before', $item ); ?><?php echo esc_html( $item['ticket_price'] ); ?><?php $this->render_currency_symbol( $symbol, 'after', $item ); ?></h5>
						<?php
					else :
						echo '&nbsp;';
					endif;
					?>
					<?php
					if ( ! empty( $item['date_text'] ) ) :
						?>
						<div <?php $this->parent->print_render_attribute_string( 'date_class' ); ?>><?php echo esc_html( $item['date_text'] ); ?></div>
						<?php
					else :
						echo '&nbsp;';
					endif;
					?>
				</li>
				<?php
				$count++;
				endforeach;
			?>
		</ul>
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
