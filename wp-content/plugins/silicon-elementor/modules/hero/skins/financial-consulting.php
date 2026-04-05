<?php
namespace SiliconElementor\Modules\Hero\Skins;

use Elementor;
use Elementor\Skin_Base;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use SiliconElementor\Core\Utils as SN_Utils;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Brand Carousel
 */
class Financial_Consulting extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'financial-consulting';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Financial Consulting', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/hero/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/hero/section_classes/after_section_end', [ $this, 'update_control_financial_skin' ], 20 );
	}

	/**
	 * Added control of the Content tab.
	 *
	 * @param Widget_Base $widget The widget settings.
	 */
	public function update_control_financial_skin( Widget_Base $widget ) {
		$this->parent = $widget;
		$disable_controls = [
			'',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'financial-consulting',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'icon_class1',
			]
		);

		$this->start_controls_section(
			'section_color_classes',
			[
				'label' => esc_html__( 'Text Styles', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_text_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .si_title' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .si_title',

			]
		);

		$this->add_control(
			'sub_title_text_color',
			[
				'label'     => esc_html__( 'Subtitle Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => [
					'{{WRAPPER}} .si_subtitle' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .si_subtitle',

			]
		);

		$this->end_controls_section();

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

		$title_class = [ 'si_title' ];

		if ( $settings['title_class'] ) {
			$title_class[] = $settings['title_class'];
		}

		$parent->add_render_attribute(
			'title_attribute',
			[
				'class' => $title_class,

			]
		);

		$sub_title_class = [ 'si_subtitle' ];

		if ( $settings['subtitle_class'] ) {
			$sub_title_class[] = $settings['subtitle_class'];
		}

		$parent->add_render_attribute(
			'sub_title_attribute',
			[
				'class' => $sub_title_class,

			]
		);

		$lead_class = [];

		if ( $settings['lead_class'] ) {
			$lead_class[] = $settings['lead_class'];
		}

		$parent->add_render_attribute(
			'lead_attribute',
			[
				'class' => $lead_class,

			]
		);

		if ( 'yes' === $settings['enable_shadow'] ) {
			$parent->add_render_attribute( 'button_1', 'class', 'shadow-' . $settings['button_type'] );
		}

		if ( 'active' === $settings['button_variant'] ) {
			$parent->add_render_attribute( 'button_1', 'class', 'active' );
		}

		if ( 'disabled' === $settings['button_variant'] ) {
			$parent->add_render_attribute( 'button_1', 'class', 'disabled' );
		}

		$button_class = [ 'btn', 'silicon-button' ];

		if ( 'outline' === $settings['button_variant'] ) {
			$button_class[] = 'btn-outline-' . $settings['button_type'];
		} elseif ( 'soft' === $settings['button_variant'] ) {
			$button_class[] = 'btn-' . $settings['button_type'] . '-soft';
		} elseif ( 'link' === $settings['button_variant'] ) {
			$button_class[] = 'btn-' . $settings['button_type'] . '-link';
		} else {
			$button_class[] = 'btn-' . $settings['button_type'];
		}

		if ( ! empty( $settings['button_size'] ) && 'link' !== $settings['button_variant'] ) {
			$button_class[] = 'btn-' . $settings['button_size'];
		}

		if ( ! empty( $settings['button_shape'] ) && 'link' !== $settings['button_variant'] ) {
			$button_class[] = $settings['button_shape'];
		}

		if ( ! empty( $settings['button_class1'] ) ) {
			$button_class[] = $settings['button_class1'];
		}

		if ( 'link' === $settings['button_type'] ) {
			$button_class = 'fw-medium ' . $settings['button_class1'];
		}

		$parent->add_render_attribute( 'button_1', 'class', $button_class );

		$migrated2 = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new2   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( 'yes' === $settings['enable_shadow1'] ) {
			$parent->add_render_attribute( 'button_2', 'class', 'shadow-' . $settings['button_type1'] );
		}

		if ( 'active' === $settings['button_variant1'] ) {
			$parent->add_render_attribute( 'button_2', 'class', 'active' );
		}

		if ( 'disabled' === $settings['button_variant1'] ) {
			$parent->add_render_attribute( 'button_2', 'class', 'disabled' );
		}

		$btn_class = [ 'btn', 'silicon-button-button-2' ];

		if ( 'outline' === $settings['button_variant1'] ) {
			$btn_class[] = 'btn-outline-' . $settings['button_type1'];
		} elseif ( 'soft' === $settings['button_variant1'] ) {
			$btn_class[] = 'btn-' . $settings['button_type1'] . '-soft';
		} elseif ( 'link' === $settings['button_variant1'] ) {
			$btn_class[] = 'btn-' . $settings['button_type1'] . '-link';
		} else {
			$btn_class[] = 'btn-' . $settings['button_type1'];
		}

		if ( ! empty( $settings['button_size1'] ) && 'link' !== $settings['button_variant1'] ) {
			$btn_class[] = 'btn-' . $settings['button_size1'];
		}

		if ( ! empty( $settings['button_shape1'] ) && 'link' !== $settings['button_variant1'] ) {
			$btn_class[] = $settings['button_shape1'];
		}

		if ( ! empty( $settings['button_class2'] ) ) {
			$btn_class[] = $settings['button_class2'];
		}

		$parent->add_render_attribute( 'button_2', 'class', $btn_class );

		$migrated3 = isset( $settings['__fa4_migrated']['selected_icon1'] );
		$is_new3   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		?>
		<div class="container-fluid position-relative position-lg-absolute top-0 start-0 h-100">
			<div class="row h-100 me-n4 me-n2">
				<div class="col-lg-7 position-relative">
					<div class="d-none d-sm-block d-lg-none" style="height: 400px;"></div>
					<div class="d-sm-none" style="height: 300px;"></div>
					<div class="jarallax position-absolute top-0 start-0 w-100 h-100 rounded-3 rounded-start-0 overflow-hidden" data-jarallax data-speed="0.5">
						<div class="jarallax-img" style="background-image: url(<?php echo esc_html( $settings['hero_image']['url'] ); ?>"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="container position-relative zindex-5 pt-lg-5 px-0 px-lg-3">
			<div class="row pt-lg-5 mx-n4 mx-lg-n3">
				<div class="col-xl-6 col-lg-7 offset-lg-5 offset-xl-6 pb-5">
				<!-- Card -->
				<div class="card bg-dark border-light overflow-hidden py-4 px-2 p-sm-4">
					<span class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(255,255,255,.05);"></span>
					<div class="card-body position-relative zindex-5">
						<p <?php $parent->print_render_attribute_string( 'sub_title_attribute' ); ?>><?php echo esc_html( $settings['sub_title'] ); ?></p>
						<h1 <?php $parent->print_render_attribute_string( 'title_attribute' ); ?>><?php echo esc_html( $settings['title'] ); ?></h1>
						<p <?php $parent->print_render_attribute_string( 'lead_attribute' ); ?>><?php echo esc_html( $settings['lead'] ); ?></p>
						<div class="d-flex flex-column flex-sm-row">
						<?php if ( 'yes' === $settings['enable_button1'] ) : ?>
							<a href="<?php echo esc_url( $settings['button_link']['url'] ); ?>" <?php $parent->print_render_attribute_string( 'button_1' ); ?>><?php echo esc_html( $settings['button_text'] ); ?>
								<?php
								if ( $is_new2 || $migrated2 ) :
									$icon_atts2          = [ 'aria-hidden' => 'true' ];
									$icon_atts2['class'] = 'sn-button-icon ' . $settings['icon_class'];
										Icons_Manager::render_icon( $settings['selected_icon'], $icon_atts2 );
								else :
									?>
									<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
								<?php endif; ?>
							</a>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['enable_button2'] ) : ?>
							<a href="<?php echo esc_url( $settings['button_link1']['url'] ); ?>" <?php $parent->print_render_attribute_string( 'button_2' ); ?>><?php echo esc_html( $settings['button_text1'] ); ?>
							<?php
							if ( $is_new3 || $migrated3 ) :
								$icon_atts3          = [ 'aria-hidden' => 'true' ];
								$icon_atts3['class'] = 'sn-button-icon3 ' . $settings['icon_class1'];
									Icons_Manager::render_icon( $settings['selected_icon1'], $icon_atts3 );
							else :
								?>
								<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
							<?php endif; ?>
							</a>
						<?php endif; ?>
						</a>
					</div>
				</div>
			</div>
			</div>
			<div class="col-xxl-3 col-lg-4 offset-lg-8 offset-xxl-9 pt-lg-5 mt-xxl-5">
				<!-- Contacts (List) -->
				<?php echo wp_kses_post( $settings['text_area'] ); ?>
			</div>
			</div>
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
		if ( 'hero' === $sn_tabs->get_name() ) {
			return '';
		}
		return $content;
	}
}
