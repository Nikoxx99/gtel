<?php
namespace SiliconElementor\Modules\Hero\Widgets;

use SiliconElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use SiliconElementor\Plugin;
use SiliconElementor\Core\Controls_Manager as SN_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Utils;
use SiliconElementor\Modules\Hero\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Class Hero
 */
class Hero extends Base_Widget {

	/**
	 * Skip widget.
	 *
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hero';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Hero', 'silicon-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-archive-title';
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
	 * Register the Skins with the widget.
	 *
	 * @return void
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\Financial_Consulting( $this ) );
		$this->add_skin( new Skins\Medical_Hero( $this ) );
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'silicon-elementor' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'We Take Care of Your Health', 'silicon-elementor' ),
				'label_block' => true,

			]
		);

		$this->add_control(
			'sub_title',
			[
				'label'       => esc_html__( 'Subtitle', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'PROFESSIONAL MEDICAL CENTER', 'silicon-elementor' ),
				'label_block' => true,

			]
		);

		$this->add_control(
			'lead',
			[
				'label'       => esc_html__( 'Lead', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Don\'t have insurance?', 'silicon-elementor' ),
				'label_block' => true,

			]
		);

		$this->add_control(
			'hero_image',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_action_button',
			[
				'label' => esc_html__( 'Action Buttons', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'enable_button1',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button 1', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Learn more', 'silicon-elementor' ),
				'condition' => [ 'enable_button1' => 'yes' ],
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
				'condition' => [ 'enable_button1' => 'yes' ],
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
					'enable_button1' => 'yes',
					'button_type!'   => 'link',
				],
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'link',
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
				'condition' => [ 'enable_button1' => 'yes' ],
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
					'enable_button1' => 'yes',
					'button_type!'   => 'link',
				],
			]
		);

		$this->add_control(
			'enable_shadow',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [
					'enable_button1' => 'yes',
					'button_type!'   => 'link',
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
					'enable_button1' => 'yes',
					'button_type!'   => 'link',
				],
			]
		);

		$this->add_control(
			'enable_icon1',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Icon 1', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [ 'enable_button1' => 'yes' ],
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
					'enable_button1' => 'yes',
					'enable_icon1'   => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_button2',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Button 2', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_text1',
			[
				'label'     => esc_html__( 'Button Text', 'silicon-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Learn more', 'silicon-elementor' ),
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'button_link1',
			[
				'label'     => esc_html__( 'Link', 'silicon-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'button_type1',
			[
				'label'     => esc_html__( 'Type', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'link',
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
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'button_variant1',
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
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'button_shape1',
			[
				'label'     => esc_html__( 'Shape', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''             => esc_html__( 'Default', 'silicon-elementor' ),
					'rounded-pill' => esc_html__( 'Pill', 'silicon-elementor' ),
					'rounded-0'    => esc_html__( 'Square', 'silicon-elementor' ),
				],
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'enable_shadow1',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Shadow', 'silicon-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'button_size1',
			[
				'label'     => esc_html__( 'Size', 'silicon-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''   => esc_html__( 'Default', 'silicon-elementor' ),
					'sm' => esc_html__( 'Small', 'silicon-elementor' ),
					'lg' => esc_html__( 'Large', 'silicon-elementor' ),
				],
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);
		$this->add_control(
			'enable_icon2',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Icon 2', 'silicon-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'  => esc_html__( 'Disable', 'silicon-elementor' ),
				'condition' => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'selected_icon1',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'condition'        => [
					'enable_button2' => 'yes',
					'enable_icon2'   => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional',
			[
				'label' => esc_html__( 'Additional Content', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'text_area',
			[
				'label' => esc_html__( 'List', 'silicon-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_classes',
			[
				'label' => esc_html__( 'Classes', 'silicon-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_class',
			[
				'label'       => esc_html__( 'Title Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'display-4', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the Title', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'subtitle_class',
			[
				'label'       => esc_html__( 'Subtitle Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'fs-xl', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the subtitle', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'lead_class',
			[
				'label'       => esc_html__( 'Lead Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'fs-lg', 'silicon-elementor' ),
				'description' => esc_html__( 'Additional CSS class that you want to apply to the lead', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( 'Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'button_class1',
			[
				'label'       => esc_html__( 'Button Class1', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
				'condition'   => [ 'enable_button1' => 'yes' ],
			]
		);

		$this->add_control(
			'button_class2',
			[
				'label'       => esc_html__( 'Button Class2', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
				'condition'   => [ 'enable_button2' => 'yes' ],
			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
				'condition'   => [ 'enable_icon1' => 'yes' ],
			]
		);

		$this->add_control(
			'icon_class1',
			[
				'label'       => esc_html__( 'Icon Class1', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image', 'silicon-elementor' ),
				'condition'   => [ 'enable_icon2' => 'yes' ],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {

	}
}
