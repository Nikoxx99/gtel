<?php
/**
 * Silicon engine room
 *
 * @package silicon
 */

/**
 * Assign the Silicon version to a var
 */
$theme           = wp_get_theme( 'silicon' );
$silicon_version = $theme['Version'];

/**
 * Define Constants
 */
define( 'SILICON_THEME_DIR', trailingslashit( get_template_directory() ) );
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$silicon = (object) array(
	'version'    => $silicon_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require get_template_directory() . '/inc/class-silicon.php',
	'customizer' => require get_template_directory() . '/inc/customizer/class-silicon-customizer.php',
);

require get_template_directory() . '/inc/silicon-functions.php';
require get_template_directory() . '/inc/silicon-template-hooks.php';
require get_template_directory() . '/inc/silicon-template-functions.php';
require get_template_directory() . '/inc/wordpress-shims.php';
require get_template_directory() . '/inc/customizer/silicon-customizer-functions.php';

/**
 * Menu Walker
 */
require get_template_directory() . '/inc/classes/class-wp-bootstrap-navwalker.php';

if ( function_exists( 'wpforms' ) ) {
	$silicon->wpforms = require get_template_directory() . '/inc/wpforms/class-silicon-wpforms.php';
}

if ( class_exists( 'Jetpack' ) ) {
	$silicon->jetpack = require get_template_directory() . '/inc/jetpack/class-silicon-jetpack.php';
}

if ( silicon_is_acf_activated() ) {
	$silicon->acf = require get_template_directory() . '/inc/acf/class-silicon-acf.php';

	require get_template_directory() . '/inc/acf/silicon-acf-functions.php';
	require get_template_directory() . '/inc/acf/silicon-acf-template-functions.php';
	require get_template_directory() . '/inc/acf/silicon-acf-template-hooks.php';
}

if ( is_admin() ) {
	$silicon->admin = require get_template_directory() . '/inc/admin/class-silicon-admin.php';

	/**
	 * TGM Plugin Activation class.
	 */
	require get_template_directory() . '/inc/classes/class-tgm-plugin-activation.php';
	$silicon->plugin_install = require get_template_directory() . '/inc/admin/class-silicon-plugin-install.php';

	if ( silicon_is_ocdi_activated() ) {
		$silicon->ocdi = require get_template_directory() . '/inc/ocdi/class-silicon-ocdi.php';
	}
}

/**
 * Functions used for Silicon Custom Theme Color
 */
require get_template_directory() . '/inc/silicon-custom-color-functions.php';

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 */
