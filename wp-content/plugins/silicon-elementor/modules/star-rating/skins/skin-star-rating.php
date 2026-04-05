<?php
namespace SiliconElementor\Modules\StarRating\Skins;

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

/**
 * Skins Star Rating Silicon
 */
class Skin_Star_Rating extends Skin_Base {
	/**
	 * Constructor.
	 *
	 * @param Elementor\Widget_Base $parent The widget settings.
	 * @return void
	 */
	public function __construct( Elementor\Widget_Base $parent ) {
		parent::__construct( $parent );
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/star-rating/section_rating/after_section_end', [ $this, 'add_content_control' ], 10 );
		add_action( 'elementor/element/star-rating/section_stars_style/after_section_end', [ $this, 'modifying_style_sections' ], 10 );
	}

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-star-rating';
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
			'rating_scale',
			'rating',
			'star_style',
			'unmarked_star_style',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin!' => 'si-star-rating',
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
			'rating_star',
			[
				'label'   => esc_html__( 'Rating Star', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				],
				'default' => '5',
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'title',
			]
		);

		$this->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Pre Title', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Pre title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'review_desc',
			[
				'label'       => esc_html__( 'Reviews', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter Your reviews', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Update control of the style tab.
	 */
	public function modifying_style_sections() {

		$disable_controls = [
			'title_gap',
		];

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin' => '',
					],
				]
			);
		}

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'title_gap',
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'before_title_color',
			[
				'label'     => esc_html__( 'Pre Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-star-title-before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'before_title_typography',
				'selector' => '{{WRAPPER}} .sn-star-title-before',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'before_title_class',
			[
				'label'       => esc_html__( 'Pre Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'reviews_color',
			[
				'label'     => esc_html__( 'Review Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sn-star-rating__desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'reviews_desc_typography',
				'selector' => '{{WRAPPER}} .sn-star-rating__desc',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'reviews_class_desc',
			[
				'label'       => esc_html__( 'Review Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'seperator'   => 'before',
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'icon_space',
			]
		);

		$this->add_control(
			'icon_wrap_class',
			[
				'label'       => esc_html__( 'Icon Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Class', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <div> tag', 'silicon-elementor' ),
			]
		);

		$this->parent->update_control(
			'icon_size',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .sn-star-rating'        => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->parent->update_control(
			'icon_space',
			[
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
					'body:not(.rtl) {{WRAPPER}} .sn-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .sn-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->parent->update_control(
			'stars_color',
			[

				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sn-star-rating i:before' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->parent->update_control(
			'stars_unmarked_color',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sn-star-rating i.opacity-75:before' => 'color: {{VALUE}} !important;',
				],
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
			'rating_star',
			'icon_color',
			'icon_wrap_class',
			'title_class',
			'reviews_class_desc',
			'before_title_class',
			'before_title',
			'review_desc',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$parent->add_render_attribute( 'icon', 'class', [ 'sn-star-rating', $skin_settings['icon_wrap_class'] ] );

		$parent->add_render_attribute( 'title_before', 'class', [ 'sn-star-title-before', $skin_settings['before_title_class'] ] );

		$parent->add_render_attribute( 'title', 'class', [ 'elementor-star-rating__title', $skin_settings['title_class'] ] );

		$parent->add_render_attribute( 'reviews_desc', 'class', [ 'sn-star-rating__desc', $skin_settings['reviews_class_desc'] ] );

		?>
		<?php if ( ! empty( $skin_settings['before_title'] ) ) : ?>
			<p <?php $parent->print_render_attribute_string( 'title_before' ); ?>><?php echo esc_html( $skin_settings['before_title'] ); ?></p>
		<?php endif; ?>
			<div <?php $parent->print_render_attribute_string( 'icon' ); ?>>
				<?php
				for ( $i = 0; $i < 5; $i++ ) {
					$icon_class = 'sn-rating-icon';
					$diff       = $skin_settings['rating_star'] - $i;
					if ( $diff > 0.5 ) {
						$icon_class = $icon_class . ' bx bxs-star text-warning';
					} else {
						$icon_class = $icon_class . ' bx bx-star text-muted opacity-75';
					}
					?>
						<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
					<?php
				}
				?>
			</div>
			<?php if ( ! Utils::is_empty( $settings['title'] ) ) : ?>
			<h3 <?php $parent->print_render_attribute_string( 'title' ); ?>><?php echo esc_html( $settings['title'] ); ?></h3>
			<?php endif; ?>
			<?php if ( ! empty( $skin_settings['review_desc'] ) ) : ?>
			<p <?php $parent->print_render_attribute_string( 'reviews_desc' ); ?>><?php echo esc_html( $skin_settings['review_desc'] ); ?></p>
			<?php endif; ?>
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
		if ( 'star-rating' === $widget->get_name() ) {
			return '';
		}
		return $content;
	}
}
