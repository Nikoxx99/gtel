<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package silicon
 */

get_header();

	$page_variant = get_theme_mod( '404_version', 'v1' );
	silicon_get_template( '404/404-' . $page_variant . '.php' );

get_footer();
