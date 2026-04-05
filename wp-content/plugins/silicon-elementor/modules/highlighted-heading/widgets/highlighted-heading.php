<?php
namespace SiliconElementor\Modules\HighlightedHeading\Widgets;

use SiliconElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use SiliconElementor\Plugin;
use SiliconElementor\Core\Controls_Manager as SN_Controls_Manager;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Highlighted_Heading
 */
class Highlighted_Heading extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'highlighted-heading';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Highlighted Heading', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-heading';
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'silicon' ];
	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'before_title',
			[
				'label'       => esc_html__( 'Before Highlighted Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'silicon-elementor' ),
				'default'     => 'Welcome to ',
				'description' => esc_html__( 'Use <br> to break into two lines', 'silicon-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'highlighted_text',
			[
				'label'       => esc_html__( 'Highlighted Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'after_title',
			[
				'label'       => esc_html__( 'After Highlighted Text', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'placeholder' => esc_html__( 'Enter your title', 'silicon-elementor' ),
				'description' => esc_html__( 'Use <br> to break into two lines', 'silicon-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'link',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link_css',
			[
				'label'       => esc_html__( 'Anchor Tag CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the <a> tag', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => esc_html__( 'Size', 'silicon-elementor' ),
				'type'    => SN_Controls_Manager::FONT_SIZE,
				'default' => '',
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'silicon-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'    => 'H1',
					'h2'    => 'H2',
					'h3'    => 'H3',
					'h4'    => 'H4',
					'h5'    => 'H5',
					'h6'    => 'H6',
					'div'   => 'div',
					'span'  => 'span',
					'small' => 'small',
					'p'     => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'silicon-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'silicon-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_highlighted_style',
			[
				'label' => esc_html__( 'Title', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .silicon-elementor-highlighted-heading__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .silicon-elementor-highlighted-heading__title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'title_css',
			[
				'label'       => esc_html__( 'Additional CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the title', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Highlighted Text', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'highlight_color',
			[
				'label'     => esc_html__( 'Highlight Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .silicon-elementor-highlighted-heading__highlighted-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'highlighted_typography',
				'selector' => '{{WRAPPER}} .silicon-elementor-highlighted-heading__highlighted-text',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'before_css',
			[
				'label'       => esc_html__( 'Before Text CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the before highlighted text', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'highlighted_css',
			[
				'label'       => esc_html__( 'Highlighted CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the highlighted text', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'after_css',
			[
				'label'       => esc_html__( 'After Text CSS', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the after highlighted text', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_heading',
			[
				'label' => esc_html__( 'Style', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'          => esc_html__( 'Width', 'silicon-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%', 'px', 'vw' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .silicon-elementor-highlighted-heading__title' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label'          => esc_html__( 'Max Width', 'silicon-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%', 'px', 'vw' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .silicon-elementor-highlighted-heading__title' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'          => esc_html__( 'Height', 'silicon-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units'     => [ 'px', 'vh' ],
				'range'          => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .silicon-elementor-highlighted-heading__title' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

	}

	/**
	 * Render Icon.
	 *
	 * @param array $settings the widget settings.
	 * @return void
	 */
	protected function render_icon( $settings ) {

		$has_icon = ! empty( $settings['icon'] );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}

		if ( $has_icon ) : ?>
			<span class="elementor-icon-box-icon">
				<?php Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</span>
			<?php
		endif;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['highlighted_text'] && '' === $settings['before_title'] ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'silicon-elementor-highlighted-heading__title' );

		if ( ! empty( $settings['title_css'] ) ) {
			$this->add_render_attribute( 'title', 'class', $settings['title_css'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', $settings['size'] );
		}

		$this->add_render_attribute( 'highlight', 'class', 'silicon-elementor-highlighted-heading__highlighted-text' );

		if ( ! empty( $settings['highlighted_css'] ) ) {
			$this->add_render_attribute( 'highlight', 'class', $settings['highlighted_css'] );
		}

		if ( ! empty( $settings['highlighted_text'] ) ) {
			$highlighted_text = '<span ' . $this->get_render_attribute_string( 'highlight' ) . '>' . $settings['highlighted_text'] . '</span>';
		} else {
			$highlighted_text = '';
		}

		/**
		 * Wrap before text.
		 */
		$before_text = '';
		$this->add_render_attribute( 'before_text', 'class', 'si-multipurpose-text__before' );
		if ( ! empty( $settings['before_css'] ) ) {
			$this->add_render_attribute( 'before_text', 'class', $settings['before_css'] );
		}

		if ( ! empty( $settings['before_title'] ) ) {
			$before_text = '<span ' . $this->get_render_attribute_string( 'before_text' ) . '>' . $settings['before_title'] . '</span>';
		}

		/**
		 * Wrap After text.
		 */
		$after_text = '';
		$this->add_render_attribute( 'after_text', 'class', 'si-multipurpose-text__before' );
		if ( ! empty( $settings['after_css'] ) ) {
			$this->add_render_attribute( 'after_text', 'class', $settings['after_css'] );
		}

		if ( ! empty( $settings['after_title'] ) ) {
			$after_text = '<span ' . $this->get_render_attribute_string( 'after_text' ) . '>' . $settings['after_title'] . '</span>';
		}

		$title = $before_text . $highlighted_text . $after_text;

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );
			$this->add_render_attribute( 'url', 'class', [ 'text-decoration-none', $settings['link_css'] ] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $title );

		echo wp_kses_post( $title_html );
	}
}
