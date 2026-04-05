<?php
namespace SiliconElementor\Modules\NavTabs\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Repeater;
use SiliconElementor\Core\Controls_Manager as SN_Controls_Manager;
use Elementor;
use SiliconElementor\Core\Utils as SNUtils;

/**
 * Skin Button Silicon
 */
class Skin_Nav_Style_3 extends Skin_Base {
	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'si-nav-author-skin';
	}
	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Nav Authors', 'silicon-elementor' );
	}

	/**
	 * Register Control Actions in the skin.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		add_filter( 'silicon-elementor/widget/si-nav-tabs/print_template', [ $this, 'skin_print_template' ], 10, 2 );
		add_action( 'elementor/element/si-nav-tabs/section_list/after_section_end', [ $this, 'remove_nav_tabs_features_widget_controls' ], 20 );
		add_action( 'elementor/element/si-nav-tabs/section_list_style/after_section_end', [ $this, 'remove_style_list_controls' ], 30 );
	}

	/**
	 * Removing Feature section controls from content tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_style_list_controls( $widget ) {
		$this->parent       = $widget;
		$update_control_ids = [ 'section_list_style' ];
		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-nav-tabs-skin', 'si-nav-style-skin', 'si-nav-author-skin' ],
					],
				]
			);
		}
		$this->start_controls_section(
			'author_section_list_style',
			[
				'label' => esc_html__( 'Author List', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'author_tabs_style' );

		$this->start_controls_tab(
			'author_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'author_tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab_author .nav-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_title_typography',
				'selector' => '{{WRAPPER}} .si-nav__tab_author .nav-item .si-elementor_author_title',

			]
		);

		$this->add_control(
			'author_tab_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab_author .nav-link' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'list_author_name_class',
			[
				'label'       => esc_html__( 'Author Name Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Author Name class', 'silicon-elementor' ),
				'default'     => esc_html__( 'd-block mb-0 fs-lg fw-semibold', 'silicon-elementor' ),
			]
		);
		$this->add_control(
			'author_image_width_spacing',
			[
				'label'      => esc_html__( 'Image Width Spacing', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 56,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1440,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .si_elementor__image' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'author_image_height_spacing',
			[
				'label'      => esc_html__( 'Image Width Spacing', 'silicon-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 56,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1440,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .si_elementor__image' => 'height: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'author_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'author_tab_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab_author .nav-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_tab_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab_author .nav-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'author_tab_active',
			[
				'label' => esc_html__( 'Active', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'author_tab_active_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab_author .nav-link.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_tab_background_active_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => [
					'{{WRAPPER}}  .si-nav__tab_author .nav-link.active' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	/**
	 * Removing Feature section controls from content tab.
	 *
	 * @param array $widget The widget settings.
	 * @return void
	 */
	public function remove_nav_tabs_features_widget_controls( $widget ) {
		$update_control_ids = [ 'nav_tabs' ];
		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				[
					'condition' => [
						'_skin!' => [ 'si-nav-tabs-skin', 'si-nav-style-skin', 'si-nav-author-skin' ],
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
		$repeater->add_control(
			'list_author',
			[
				'label'       => esc_html__( 'Author Name', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Author Name', 'silicon-elementor' ),
				'default'     => esc_html__( 'Author List', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'list_author_byline',
			[
				'label'       => esc_html__( 'Author Byline', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Author byline', 'silicon-elementor' ),
				'default'     => esc_html__( 'Author Byline', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'author_list_url',
			[
				'label'       => esc_html__( 'List Link', 'silicon-elementor' ),
				'default'     => [
					'url' => esc_url( '', 'silicon-elementor' ),
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,

			]
		);

		$repeater->add_control(
			'author_image',
			[
				'label'   => esc_html__( 'Choose Image', 'silicon-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'author_image',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'author_image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Image Class', 'silicon-elementor' ),
				'default'     => esc_html__( 'rounded-circle me-md-3 me-sm-0 me-3 mb-md-0 mb-sm-2', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'author_nav_tabs',
			[
				'label'       => esc_html__( 'Author List', 'silicon-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'list_author'        => esc_html__( 'Jack Taylor', 'silicon-elementor' ),
						'list_author_byline' => esc_html__( 'Co-founder of Lorem Company', 'silicon-elementor' ),
					],
					[
						'list_author'        => esc_html__( 'Annette Black', 'silicon-elementor' ),
						'list_author_byline' => esc_html__( 'VP of Strategy, Stretto Inc.', 'silicon-elementor' ),
					],
					[
						'list_author'        => esc_html__( 'Mel Gibson', 'silicon-elementor' ),
						'list_author_byline' => esc_html__( 'Founder & CEO, Uber', 'silicon-elementor' ),
					],
				],
				'title_field' => '{{{ list_author }}}',
			]
		);
		$this->parent->end_injection();
	}

	/**
	 * Render the skin in the frontend.
	 */
	public function render() {
		$widget          = $this->parent;
		$settings        = $widget->get_settings_for_display();
		$author_list_id  = uniqid( 'tabs-' );
		$author_nav_tabs = $this->get_instance_value( 'author_nav_tabs' );

		$widget->add_render_attribute(
			'author_list_view',
			[
				'class' => [ 'si-nav__tab_author', $settings['ul_wrap'] ],
				'role'  => 'tablist',
			]
		);

		?><ul <?php $widget->print_render_attribute_string( 'author_list_view' ); ?>>
		<?php
		foreach ( $author_nav_tabs as $index => $item ) :
			$count       = $index + 1;
			$active      = '';
			$selected    = 'false';
			$title_class = $this->get_instance_value( 'list_author_name_class' );

			if ( 1 === $count ) {
				$active   = 'active';
				$selected = 'true';
				$widget->add_render_attribute( 'author_list_item_view' . $count, 'class', $active );
			}
			$widget->add_render_attribute(
				'author_list_item_view' . $count,
				[
					'href'           => '-#' . $item['author_list_url']['url'],
					'class'          => [ 'si-elementor__tab', $settings['li_wrap'] ],
					'role'           => 'tab',
					'id'             => $author_list_id . $count,
					'data-bs-toggle' => 'tab',
					'aria-controls'  => $item['author_list_url']['url'],
					'aria-selected'  => $selected,
				]
			);

			$image_class = [ ' si_elementor__image' ];
			$widget->add_render_attribute(
				'list_item_author_title' . $count,
				[
					'class' => [ $title_class, 'si-elementor_author_title' ],
				]
			);

			if ( $item['author_image_class'] ) {
				$image_class[] = $item['author_image_class'];
			}
			?>
			<li class="nav-item mb-3 list-unstyled" role="presentation">
				<a <?php $widget->print_render_attribute_string( 'author_list_item_view' . $count ); ?>>
			<?php
				$image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'author_image', 'author_image' ) );
			echo wp_kses_post( SNUtils::add_class_to_image_html( $image_html, $image_class ) );
			?>
					<div>
						<span <?php $widget->print_render_attribute_string( 'list_item_author_title' . $count ); ?>><?php echo esc_html( $item['list_author'] ); ?></span>
						<?php echo esc_html( $item['list_author_byline'] ); ?>
					</div>
				</a>
			</li>
			<?php
		endforeach;
		?>
		</ul>
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
		if ( 'si-nav-tabs' === $sn_tabs->get_name() ) {
			return '';
		}
		return $content;
	}
}
