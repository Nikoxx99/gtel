<?php
namespace SiliconElementor\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils;


/**
 * Icons Manager
 */
final class Icons_Manager {
	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'additional_tabs' ], 20 );
	}

	/**
	 * Adding $tabs ( boxicon tabs ) in the icon library.
	 *
	 * @param  array $tabs Adding additional tabs to the icon library.
	 *
	 * @return string
	 */
	public function additional_tabs( $tabs ) {
		$new_tabs = [
			'box-icons' => [
				'name'          => 'box-icons',
				'label'         => esc_html__( 'Box Icons', 'silicon-elementor' ),
				'url'           => get_template_directory_uri() . '/assets/vendor/boxicons/css/boxicons.css',
				'enqueue'       => [],
				'prefix'        => '',
				'displayPrefix' => 'bx ',
				'labelIcon'     => 'bx bxl-meta',
				'ver'           => 'v2.1.1',
				'fetchJson'      => get_template_directory_uri() . '/assets/vendor/boxicons/dist/bxicons.js',

				'native'        => false,
			],
		];

		return array_merge( $tabs, $new_tabs );
	}
}
