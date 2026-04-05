<?php
namespace SiliconElementor\Modules\NavTabs\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use SiliconElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use SiliconElementor\Core\Controls_Manager as SI_Controls_Manager;
use Elementor\Icons_Manager;
use SiliconElementor\Modules\NavTabs\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Nav Tabs Silicon Elementor Widget.
 */
class Nav_Tabs extends Base_Widget {



	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-nav-tabs';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Nav Tabs', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'headline', 'heading', 'animation', 'typed' ];
	}

	/**
	 * Register Skins for pricing widget.
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Nav_Tabs( $this ) );
		$this->add_skin( new Skins\Skin_Nav_Style_2( $this ) );
		$this->add_skin( new Skins\Skin_Nav_Style_3( $this ) );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'section_list',
			[
				'label' => esc_html__( 'Nav Tabs List', 'silicon-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'list',
			[
				'label'       => esc_html__( 'List Item', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item', 'silicon-elementor' ),
				'default'     => esc_html__( 'List Item', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'enable_link',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Link?', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Show', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'silicon-elementor' ),
			]
		);

		$repeater->add_control(
			'list_url',
			[
				'label'       => esc_html__( 'List Link', 'silicon-elementor' ),
				'default'     => [
					'url' => esc_url( '#', 'silicon-elementor' ),
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'silicon-elementor' ),
				'type'        => Controls_Manager::URL,
				'condition'   => [
					'enable_link' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'content_id',
			[
				'label'       => esc_html__( 'Tab Content ID', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tab Content Id', 'silicon-elementor' ),
				'condition'   => [
					'enable_link!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label'   => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'bx bx-star ',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Icon Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'nav_tabs',
			[
				'label'       => esc_html__( 'Nav List', 'silicon-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title' => esc_html__( 'Project management', 'silicon-elementor' ),
					],
					[
						'title' => esc_html__( 'Remote work', 'silicon-elementor' ),
					],
					[
						'title' => esc_html__( 'Product release', 'silicon-elementor' ),
					],
					[
						'title' => esc_html__( 'Campaign planning', 'silicon-elementor' ),
					],
				],
				'title_field' => '{{{ list }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_style',
			[
				'label' => esc_html__( 'List', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'tab_normal',
			[
				'label' => esc_html__( 'Normal', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_title_typography',
				'selector' => '{{WRAPPER}} .si-nav__tab .nav-item',

			]
		);

		$this->add_control(
			'tab_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link .si-nav_icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'list_icon_typography',
				'selector' => '{{WRAPPER}} .si-nav__tab .si-nav_icon',

			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover',
			[
				'label' => esc_html__( 'Hover', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_background_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color_hover',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link:hover .si-nav_icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active',
			[
				'label' => esc_html__( 'Active', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label'     => esc_html__( 'Text Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_background_active_color',
			[
				'label'     => esc_html__( 'Background Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => [
					'{{WRAPPER}}  .si-nav__tab .nav-link.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color_active',
			[
				'label'     => esc_html__( 'Icon Color', 'silicon-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab .nav-link.active .si-nav_icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ul_wrap',
			[
				'label'       => esc_html__( 'List Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'flex-nowrap justify-content-lg-center overflow-auto pb-2 mb-3 mb-lg-4', 'silicon-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Wrap Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'li_wrap',
			[
				'label'       => esc_html__( 'List item Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Wrap Class', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'icon_svg_spacing',
			[
				'label'     => esc_html__( 'Icon and SVG Spacing', 'silicon-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .si-nav__tab i'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .si-nav__tab svg' => 'margin-right: {{SIZE}}{{UNIT}};',

				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 8,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$list_id  = uniqid( 'tabs-' );

		$this->add_render_attribute(
			'list',
			[
				'class' => [ 'si-nav__tab', 'nav', 'nav-tabs', $settings['ul_wrap'] ],
				'role'  => 'tablist',
				'id'    => $list_id,
			]
		);

		?>
		<ul <?php $this->print_render_attribute_string( 'list' ); ?>>
			<?php
			foreach ( $settings['nav_tabs'] as $index => $item ) :
				$count    = $index + 1;
				$active   = '';
				$selected = 'false';

				$this->add_render_attribute(
					'list_item' . $count,
					[
						'class' => [ 'nav-item', $settings['li_wrap'] ],
						'role'  => 'presentation',
					]
				);

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$this->add_render_attribute( 'list_link_item' . $count, 'class' );
				}

				if ( isset( $item['list_url']['url'] ) ) {
					$this->add_link_attributes( 'list_link_item' . $count, $item['list_url'] );

				}

				$this->add_render_attribute(
					'list_link_item' . $count,
					[
						'class' => [ 'nav-link', $active ],
					]
				);

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$this->add_render_attribute( 'list_item' . $count, 'class' );
				}

				$this->add_render_attribute(
					'list_link' . $count,
					[
						'class'          => array( 'nav-link text-nowrap', $active ),
						'id'             => 'si-' . $item['content_id'],
						'data-bs-toggle' => 'tab',
						'data-bs-target' => '#' . $item['content_id'],
						'type'           => 'button',
						'role'           => 'tab',
						'aria-controls'  => $item['content_id'],
						'aria-selected'  => $selected,
					]
				);

				$icon_class = $item['icon_class'];

				$icon = [ $icon_class, 'si-nav_icon' ];

				if ( $item['icon']['value'] ) {
					$icon[] = $item['icon']['value'];
				}

				$this->add_render_attribute(
					'list_icon' . $count,
					[
						'class' => $icon,
					]
				);

				?>
				<li <?php $this->print_render_attribute_string( 'list_item' . $count ); ?>>
					<?php if ( isset( $item['list_url']['url'] ) ) : ?>
						<a  <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>>
							<i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i>
						<?php echo esc_html( $item['list'] ); ?></a>
						<?php
					else :
						?>
						<button <?php $this->print_render_attribute_string( 'list_link' . $count ); ?>>
							<?php if ( empty( $item['icon']['value']['url'] ) ) : ?>
								<i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i>
							<?php else : ?>
								<?php
									Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );
								?>
							<?php endif; ?>
							<?php echo esc_html( $item['list'] ); ?>
						</button>
					<?php endif; ?>
				</li>
				<?php
			endforeach;
			?>
		</ul>
		<?php
	}
}
