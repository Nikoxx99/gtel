<?php

namespace SiliconElementor\Modules\ImageGallery;

use SiliconElementor\Base\Module_Base;
use SiliconElementor\Modules\ImageGallery\Skins;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'si-basic-gallery';
	}

	/**
	 * Add actions for the module.
	 */
	public function add_actions() {
		add_action( 'elementor/widget/image-gallery/skins_init', [ $this, 'init_skins' ], 10 );
	}

	/**
	 * Add Action.
	 *
	 * @param array $widget The image gallery widget.
	 * @return void
	 */
	public function init_skins( $widget ) {
		$widget->add_skin( new Skins\Skin_Image_Gallery( $widget ) );
		$widget->add_skin( new Skins\Skin_Profile( $widget ) );
		$widget->add_skin( new Skins\Skin_Image_Grid_Parallax( $widget ) );
		$widget->add_skin( new Skins\Skin_Parallax_Gfx( $widget ) );
	}
}
