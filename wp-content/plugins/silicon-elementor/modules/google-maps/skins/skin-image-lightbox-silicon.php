<?php
namespace SiliconElementor\Modules\GoogleMaps\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Control_Media;

/**
 * Skin Button Silicon
 */
class Skin_Image_Lightbox_Silicon extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'silicon-image-lightbox';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Image with Lightbox', 'silicon-elementor' );
	}

	/**
	 * Register control actions.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_action( 'elementor/element/google_maps/section_map/after_section_end', [ $this, 'register_controls' ], 10 );
	}

	/**
	 * Added control of the Content tab.
	 *
	 * @param Widget_Base $widget The widget settings.
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$disable_controls = array();

		foreach ( $disable_controls as $control ) {
			$this->parent->update_control(
				$control,
				[
					'condition' => [
						'_skin' => 'silicon-image-lightbox',
					],
				]
			);
		}

		$widget->update_responsive_control(
			'height',
			[
				'selectors' => [
					'{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} img'    => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => '_skin',
			]
		);

		$this->parent->end_injection();

		$this->parent->start_injection(
			[
				'at' => 'after',
				'of' => 'view',
			]
		);

		$this->add_control(
			'image',
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

		$this->add_control(
			'enable_dark_image',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Enable Dark Image', 'silicon-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Enable', 'silicon-elementor' ),
				'label_on'     => esc_html__( 'Disable', 'silicon-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'dark_image',
			[
				'label'     => esc_html__( 'Dark Image', 'silicon-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					$this->get_control_id( 'enable_dark_image' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'            => esc_html__( 'Icon', 'silicon-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value' => 'bx  bxs-map',
				],
				'skin'             => 'inline',
				'label_block'      => false,
				'condition' => [
					$this->get_control_id( 'enable_dark_image!' ) => 'yes',
				],

			]
		);

		$this->add_control(
			'caption',
			[
				'label'       => esc_html__( 'Caption', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your caption', 'silicon-elementor' ),
				'description' => esc_html__( 'Use this to override the address in the maps', 'silicon-elementor' ),
			]
		);

		$this->parent->end_injection();

		$this->start_controls_section(
			'section_map_class',
			[
				'label' => esc_html__( 'Classes', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'wrap_class',
			[
				'label'       => esc_html__( 'Wrap Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'rounded-3 shadow-sm',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the image wrap', 'silicon-elementor' ),
			]
		);

		$this->add_control(
			'anchor_class',
			[
				'label'       => esc_html__( 'Anchor Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'rounded-3',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the anchor', 'silicon-elementor' ),
			]
		);
		$this->add_control(
			'image_class',
			[
				'label'       => esc_html__( ' Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <div>  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'd-dark-mode-none',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <div>', 'silicon-elementor' ),

			]
		);

		$this->add_control(
			'dark_image_class',
			[
				'label'       => esc_html__( 'Dark Image Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'title'       => esc_html__( 'Add your custom class for <div>  without the dot. e.g: my-class', 'silicon-elementor' ),
				'default'     => 'd-none d-dark-mode-block',
				'label_block' => true,
				'description' => esc_html__( 'Additional CSS class that you want to apply to the <div>', 'silicon-elementor' ),
				'condition'   => [ $this->get_control_id( 'enable_dark_image' ) => 'yes' ],

			]
		);

		$this->add_control(
			'icon_class',
			[
				'label'       => esc_html__( 'Icon Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ' display-5 position-absolute top-50 start-50 translate-middle mb-0',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the icon', 'silicon-elementor' ),
				'condition' => [
					$this->get_control_id( 'enable_dark_image!' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'caption_class',
			[
				'label'       => esc_html__( 'Caption Class', 'silicon-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'fw-medium',
				'description' => esc_html__( 'Additional CSS class that you want to apply to the caption', 'silicon-elementor' ),
				'condition'   => [
					'caption_source' => 'custom',
				],
			]
		);

		$this->parent->end_controls_section();
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
			'selected_icon',
			'caption',
			'wrap_class',
			'anchor_class',
			'icon_class',
			'caption_class',
			'image',
			'dark_image',
			'image_class',
			'dark_image_class',
			'enable_dark_image',
		];

		$skin_settings = [];

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}

		$wrap_class   = [ 'gallery' ];
		$wrap_class[] = $skin_settings['wrap_class'];

		$parent->add_render_attribute(
			'wrap_attribute',
			[
				'class' => $wrap_class,
			]
		);

		$anchor_class   = [ 'gallery-item' ];
		$anchor_class[] = $skin_settings['anchor_class'];

		$parent->add_render_attribute(
			'anchor_attribute',
			[
				'class' => $anchor_class,

			]
		);

		$icon_class   = [ 'silicon-icon' ];
		$icon_class[] = $skin_settings['icon_class'];
		$icon_class[] = $skin_settings['selected_icon']['value'];

		$parent->add_render_attribute(
			'icon_attribute',
			[
				'class' => $icon_class,
			]
		);

		$caption_class   = [ 'gallery-item-caption' ];
		$caption_class[] = $skin_settings['caption_class'];

		$parent->add_render_attribute(
			'caption_attribute',
			[
				'class' => $caption_class,
			]
		);

		$image_class = $skin_settings['image_class'];
		$image_src   = $skin_settings['image']['url'];

		$parent->add_render_attribute(
			'image_attribute',
			[
				'class' => $image_class,
				'src'   => $image_src,
				'alt'   => Control_Media::get_image_alt( $skin_settings['image'] ),
			]
		);

		$dark_image_class = $skin_settings['dark_image_class'];
		$dark_image_src   = $skin_settings['dark_image']['url'];

		$parent->add_render_attribute(
			'dark_image_attribute',
			[
				'class' => $dark_image_class,
				'src'   => $dark_image_src,
				'alt'   => Control_Media::get_image_alt( $skin_settings['dark_image'] ),
			]
		);

		$parent->add_render_attribute(
			'map_link',
			array(
				'data-iframe' => true,
				'class'       => 'gallery-item rounded-3',
			)
		);

		if ( ! empty( $settings['address'] ) ) {
			$parent->add_render_attribute( 'map_link', 'data-sub-html', '<h6 class="fs-sm text-light">' . esc_attr( $settings['address'] . '</h6>' ) );
		}

		$api_key = esc_html( get_option( 'elementor_google_maps_api_key' ) );

		$params = [
			rawurlencode( $settings['address'] ),
			absint( $settings['zoom']['size'] ),
		];

		if ( $api_key ) {
			$params[] = $api_key;
			$url      = 'https://www.google.com/maps/embed/v1/place?key=%3$s&q=%1$s&amp;zoom=%2$d';
		} else {
			$url = 'https://maps.google.com/maps?q=%1$s&amp;t=m&amp;z=%2$d&amp;output=embed&amp;iwloc=near';
		}

		$url      = vsprintf( $url, $params );
		$sub_html = $settings['address'];
		if ( ! empty( $skin_settings['caption'] ) ) {
			$sub_html = $skin_settings['caption'];
		}

		$caption = esc_html__( 'Expand the map', 'silicon-elementor' );
		if ( 'yes' === $skin_settings['enable_dark_image'] ) {
			$caption = $settings['address'];
		}

		?>
		<div <?php $parent->print_render_attribute_string( 'wrap_attribute' ); ?>>
			<a href="<?php echo esc_url( $url ); ?>" data-iframe="true" <?php $parent->print_render_attribute_string( 'anchor_attribute' ); ?> data-sub-html='<h6 class="fs-sm text-light"><?php echo esc_attr( $sub_html ); ?></h6>'>
				<?php if ( 'yes' !== $skin_settings['enable_dark_image'] ) : ?>
				<i <?php $parent->print_render_attribute_string( 'icon_attribute' ); ?>></i>
				<?php endif; ?>
				<img <?php $parent->print_render_attribute_string( 'image_attribute' ); ?>>
				<?php if ( 'yes' === $skin_settings['enable_dark_image'] ) : ?>
				<img <?php $parent->print_render_attribute_string( 'dark_image_attribute' ); ?>>
				<?php endif; ?>
				<div <?php $parent->print_render_attribute_string( 'caption_attribute' ); ?>><?php echo esc_html( $caption ); ?></div>
			</a>
		</div>
		<?php
	}
}
