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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Brand Carousel
 */
class Medical_Hero extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'medical-hero';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Medical', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/hero/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/hero/section_additional/after_section_end', [ $this, 'add_control_medical_skin' ], 10 );
		add_action( 'elementor/element/hero/section_classes/after_section_end', [ $this, 'update_control_medical_skin' ], 10 );
	}

	/**
	 * Added control of the Content tab.
	 *
	 * @param Widget_Base $widget The widget settings.
	 */
	public function add_control_medical_skin( Widget_Base $widget ) {
		$this->parent = $widget;
		$disable_controls = [
			'',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'medical-hero',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'before',
				'of' => 'text_area',
			]
		);

		$this->add_control(
			'additional_hero_image',
			[
				'label'   => esc_html__( 'Hero Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],

			]
		);

		$this->add_control(
			'addtional_title',
			[
				'label'       => esc_html__( 'Title', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Silicon Medical Center', 'silicon-elementor' ),
				'label_block' => true,

			]
		);

		$this->add_control(
			'addtional_sub_title',
			[
				'label'       => esc_html__( 'Subtitle', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Our medical center provides a wide range of health care services. We use only advanced technologies to keep your family happy and healthy, without any unexpected surprises. We appreciate your trust greatly. Our patients choose us and our services because they know we are the best.', 'silicon-elementor' ),
				'label_block' => true,

			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'text_area',
			]
		);

		$this->add_control(
			'enable_button',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button?', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Show', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_title',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Click Here', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_link',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'light',
				'options'   => [
					'primary'   => esc_html__( 'Primary', 'silicon-elementor' ),
					'secondary' => esc_html__( 'Secondary', 'silicon-elementor' ),
					'success'   => esc_html__( 'Success', 'silicon-elementor' ),
					'danger'    => esc_html__( 'Danger', 'silicon-elementor' ),
					'warning'   => esc_html__( 'Warning', 'silicon-elementor' ),
					'info'      => esc_html__( 'Info', 'silicon-elementor' ),
					'light'     => esc_html__( 'Light', 'silicon-elementor' ),
					'dark'      => esc_html__( 'Dark', 'silicon-elementor' ),
					'link'      => esc_html__( 'Link', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_variant',
			[
				'label'     => esc_html__( 'Variant', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => esc_html__( 'Default', 'silicon-elementor' ),
					'outline'  => esc_html__( 'Outline', 'silicon-elementor' ),
					'active'   => esc_html__( 'Active', 'silicon-elementor' ),
					'disabled' => esc_html__( 'Disabled', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_shape',
			[
				'label'     => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'shadow',
			[
				'label'        => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'silicon-elementor' ),
				'label_off'    => esc_html__( 'No', 'silicon-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sn-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .sn-button-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					$this->get_control_id( 'enable_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

	}


	/**
	 * Added control of the Content tab.
	 *
	 * @param Widget_Base $widget The widget settings.
	 */
	public function update_control_medical_skin( Widget_Base $widget ) {
		$this->parent = $widget;
		$disable_controls = [
			'',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'medical-hero',
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
			'section_additional_classes',
			[
				'label' => esc_html__( 'Additional Content Classes', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'addtional_title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'text-light pb-1 pb-md-3', 'silicon-elementor' ),
				'description' => esc_html__( 'CSS class that you want to apply to the Title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'addtional_sub_title_class',
			[
				'label'       => esc_html__( 'Subtitle Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'fs-lg text-light pb-2 pb-md-0 mb-4 mb-md-5', 'silicon-elementor' ),
				'description' => esc_html__( 'CSS class that you want to apply to the subtitle', 'silicon-elementor' ),
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

		$skin_control_ids = [
			'enable_button',
			'selected_icon',
			'shadow',
			'button_title',
			'button_link',
			'button_size',
			'button_shape',
			'button_variant',
			'button_type',
			'additional_hero_image',
			'addtional_title',
			'addtional_sub_title',
			'addtional_title_class',
			'addtional_sub_title_class',
			'icon_class',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$title_class = [];

		if ( $settings['title_class'] ) {
			$title_class[] = $settings['title_class'];
		}

		$parent->add_render_attribute(
			'title_attribute',
			[
				'class' => $title_class,

			]
		);

		$sub_title_class = [];

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

		$addtional_title_class = [];

		if ( $skin_settings['addtional_title_class'] ) {
			$addtional_title_class[] = $skin_settings['addtional_title_class'];
		}

		$parent->add_render_attribute(
			'addtional_title_attribute',
			[
				'class' => $addtional_title_class,

			]
		);

		$addtional_sub_title_class = [];

		if ( $skin_settings['addtional_sub_title_class'] ) {
			$addtional_sub_title_class[] = $skin_settings['addtional_sub_title_class'];
		}

		$parent->add_render_attribute(
			'addtional_sub_title_attribute',
			[
				'class' => $addtional_sub_title_class,

			]
		);

		if ( 'yes' === $skin_settings['shadow'] ) {
			$parent->add_render_attribute( 'button', 'class', 'shadow-' . $skin_settings['button_type'] );
		}

		if ( 'active' === $skin_settings['button_variant'] ) {
			$parent->add_render_attribute( 'button', 'class', 'active' );
		}

		if ( 'disabled' === $skin_settings['button_variant'] ) {
			$parent->add_render_attribute( 'button', 'class', 'disabled' );
		}

		$button_class = [ 'btn', 'silicon-button' ];

		if ( 'outline' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-outline-' . $skin_settings['button_type'];
		} elseif ( 'soft' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'] . '-soft';
		} elseif ( 'link' === $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_type'] . '-link';
		} else {
			$button_class[] = 'btn-' . $skin_settings['button_type'];
		}

		if ( ! empty( $skin_settings['button_size'] ) && 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = 'btn-' . $skin_settings['button_size'];
		}

		if ( ! empty( $skin_settings['button_shape'] ) && 'link' !== $skin_settings['button_variant'] ) {
			$button_class[] = $skin_settings['button_shape'];
		}

		$parent->add_render_attribute( 'button', 'class', $button_class );

		$migrated = isset( $skin_settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $skin_settings['icon'] ) && Icons_Manager::is_migration_allowed();

		?><div class="container position-relative zindex-5 pt-5">
			<div class="row mt-4 pt-5">
				<div class="col-xl-4 col-lg-5 text-center text-lg-start pb-3 pb-md-4 pb-lg-0">
					<h1 <?php $parent->print_render_attribute_string( 'sub_title_attribute' ); ?>><?php echo esc_html( $settings['sub_title'] ); ?></h1>
					<h3 <?php $parent->print_render_attribute_string( 'title_attribute' ); ?>><?php echo esc_html( $settings['title'] ); ?></h3>
					<p <?php $parent->print_render_attribute_string( 'lead_attribute' ); ?>><?php echo esc_html( $settings['lead'] ); ?>
					<?php if ( 'yes' === $settings['enable_button1'] ) : ?>
						<a href="<?php echo esc_url( $settings['button_link']['url'] ); ?>" <?php $parent->print_render_attribute_string( 'button_1' ); ?>><?php echo esc_html( $settings['button_text'] ); ?>
							<?php
							if ( $is_new2 || $migrated2 ) :
								$icon_atts2          = [ 'aria-hidden' => 'true' ];
								$icon_atts2['class'] = 'sn-button-icon';
									Icons_Manager::render_icon( $settings['selected_icon'], $icon_atts2 );
							else :
								?>
								<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
							<?php endif; ?>
						</a>
					<?php endif; ?>
					</p>
				</div>
				<div class="col-xl-5 col-lg-6 offset-xl-1 position-relative zindex-5 mb-5 mb-lg-0">
					<div class="card bg-primary border-0 shadow-primary py-2 p-sm-4 p-lg-5" data-jarallax-element="40" data-disable-parallax-down="lg">
						<div class="card-body p-lg-3">
						<h2 <?php $parent->print_render_attribute_string( 'addtional_title_attribute' ); ?>><?php echo esc_html( $skin_settings['addtional_title'] ); ?></h2>
						<p <?php $parent->print_render_attribute_string( 'addtional_sub_title_attribute' ); ?>><?php echo esc_html( $skin_settings['addtional_sub_title'] ); ?></p>
						<?php if ( 'yes' === $skin_settings['enable_button'] ) : ?>
							<a href="<?php echo esc_url( $skin_settings['button_link']['url'] ); ?>" <?php $parent->print_render_attribute_string( 'button' ); ?>><?php echo esc_html( $skin_settings['button_title'] ); ?>
								<?php
								if ( $is_new || $migrated ) :
									$icon_atts          = [ 'aria-hidden' => 'true' ];
									$icon_atts['class'] = 'sn-button-icon ' . $skin_settings['icon_class'];
										Icons_Manager::render_icon( $skin_settings['selected_icon'], $icon_atts );
								else :
									?>
									<i class="<?php echo esc_attr( $skin_settings['icon'] ); ?>" aria-hidden="true"></i>
								<?php endif; ?>
							</a>
						<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="d-none d-lg-block" style="margin-top: -165px;"></div>
			<div class="row align-items-end">
				<div class="col-lg-6 d-none d-lg-block">
					<img src="<?php echo esc_url( $skin_settings['additional_hero_image']['url'] ); ?>" class="rounded-3" alt="Image" data-jarallax-element="-40" data-disable-parallax-down="md">
				</div>
				<div class="col-lg-6 d-flex flex-column flex-md-row align-items-center justify-content-between">
					<div class="d-flex align-items-center ps-xl-5 mb-4 mb-md-0">
						<?php echo wp_kses_post( $settings['text_area'] ); ?>
					</div>
					<?php if ( 'yes' === $settings['enable_button2'] ) : ?>
						<a href="<?php echo esc_url( $settings['button_link1']['url'] ); ?>" <?php $parent->print_render_attribute_string( 'button_2' ); ?>><?php echo esc_html( $settings['button_text1'] ); ?>
						<?php
						if ( $is_new3 || $migrated3 ) :
							$icon_atts3          = [ 'aria-hidden' => 'true' ];
							$icon_atts3['class'] = 'sn-button-icon3';
								Icons_Manager::render_icon( $settings['selected_icon1'], $icon_atts3 );
						else :
							?>
							<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
						<?php endif; ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="d-none d-lg-block position-absolute top-0 end-0 w-50 d-flex flex-column ps-3" style="height: calc(100% - 108px);">
			<div class="w-100 h-100 overflow-hidden bg-position-center bg-repeat-0 bg-size-cover" style="background-image: url(<?php echo esc_html( $settings['hero_image']['url'] ); ?>); border-bottom-left-radius: .5rem;"></div>
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
