<?php
namespace SiliconElementor\Core;

use SiliconElementor\Base\Module_Base;
use SiliconElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module Manager
 */
final class Modules_Manager {
	/**
	 * Modules
	 *
	 * @var Module_Base[]
	 */
	private $modules = [];

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$modules = [
			'button',
			'button-icon',
			'blockquote',
			'carousel',
			'custom-attributes',
			'custom-css',
			'column',
			'highlighted-heading',
			'image',
			'logo',
			'nav-tabs',
			'nav-menu',
			'pricing',
			'section',
			'ticket',
			'query-control',
			'icon-list',
			'accordion',
			'team-member',
			'image-box',
			'slider',
			'parallax',
			'icon-box',
			'star-rating',
			'shapes',
			'icon',
			'page-settings',
			'categories-dropdown',
			'image-gallery',
			'google-maps',
			'posts',
			'lottie',
			'breadcrumb',
			'testimonial',
			'hero',
			'sidebar',
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\SiliconElementor\Modules\\' . $class_name . '\Module';

			/** Module Base @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}

		$this->init_actions();
	}

	/**
	 * Action Hooks.
	 *
	 * @return void
	 */
	public function init_actions() {
		add_action( 'elementor/widget/before_render_content', [ $this, 'before_render_content' ], 10 );
		add_action( 'elementor/widget/render_content', [ $this, 'render_content' ], 10, 2 );
		add_filter( 'elementor/widget/print_template', array( $this, 'print_template' ), 10, 2 );
	}

	/**
	 * Before Render Content.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function before_render_content( $widget ) {
		$widget_name = $widget->get_name();
		do_action( "silicon-elementor/widget/{$widget_name}/before_render_content", $widget );
	}

	/**
	 * Render Content.
	 *
	 * @param array $content The content.
	 * @param array $widget The widget.
	 * @return string
	 */
	public function render_content( $content, $widget ) {
		$widget_name = $widget->get_name();
		$content     = apply_filters( "silicon-elementor/widget/{$widget_name}/render_content", $content, $widget );
		return $content;
	}

	/**
	 * Print Template.
	 *
	 * @param array $content The content.
	 * @param array $widget The widget.
	 * @return array
	 */
	public function print_template( $content, $widget ) {
		$widget_name = $widget->get_name();
		$content     = apply_filters( "silicon-elementor/widget/{$widget_name}/print_template", $content, $widget );
		return $content;
	}

	/**
	 * Get Modules.
	 *
	 * @param string $module_name module name.
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	/**
	 * Add inline editing attributes.
	 *
	 * Define specific area in the element to be editable inline. The element can have several areas, with this method
	 * you can set the area inside the element that can be edited inline. You can also define the type of toolbar the
	 * user will see, whether it will be a basic toolbar or an advanced one.
	 *
	 * Note: When you use wysiwyg control use the advanced toolbar, with textarea control use the basic toolbar. Text
	 * control should not have toolbar.
	 *
	 * PHP usage (inside `Widget_Base::render()` method):
	 *
	 *    $this->add_inline_editing_attributes( 'text', 'advanced' );
	 *    echo '<div ' . $this->get_render_attribute_string( 'text' ) . '>' . $this->get_settings( 'text' ) . '</div>';
	 *
	 * @since 1.8.0
	 *
	 * @param string $key     Element key.
	 * @param string $toolbar Optional. Toolbar type. Accepted values are `advanced`, `basic` or `none`. Default is
	 *                        `basic`.
	 */

	/**
	 * Repeater get settings key.
	 *
	 * @param array $setting_key key.
	 * @param array $repeater_key repeater key.
	 *  @param array $repeater_item_index index.
	 * @return array
	 */
	public function get_repeater_setting_key( $setting_key, $repeater_key, $repeater_item_index ) {
		return implode( '.', [ $repeater_key, $repeater_item_index, $setting_key ] );
	}

	/**
	 * Repeater get settings key.
	 *
	 * @param array  $widget widget.
	 * @param array  $key key.
	 *  @param string $toolbar type.
	 * @return void
	 */
	public function add_inline_editing_attributes( $widget, $key, $toolbar = 'basic' ) {
		if ( ! Plugin::elementor()->editor->is_edit_mode() ) {
			return;
		}

		$widget->add_render_attribute(
			$key,
			[
				'class'                      => 'elementor-inline-editing',
				'data-elementor-setting-key' => $key,
			]
		);

		if ( 'basic' !== $toolbar ) {
			$widget->add_render_attribute(
				$key,
				[
					'data-elementor-inline-editing-toolbar' => $toolbar,
				]
			);
		}
	}
}
