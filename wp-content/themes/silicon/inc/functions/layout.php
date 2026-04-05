<?php
/**
 * Functions related to Layout.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get if a given page has sidebar or not.
 *
 * @param string $where The page where it is required to check sidebar.
 * @return bool
 */
function silicon_has_sidebar( $where = null ) {
	return silicon_has_blog_sidebar();
}

/**
 * Get the Blog layout.
 *
 * @return string
 */
function silicon_get_blog_layout() {
	$sidebar_layout = silicon_get_blog_sidebar_layout();
	return apply_filters( 'silicon_blog_layout', $sidebar_layout );
}

/**
 * Get the Blog Sidebar layout.
 *
 * @return string
 */
function silicon_get_blog_sidebar_layout() {
	$sidebar_layout = get_theme_mod( 'blog_sidebar', 'sidebar-right' );
	return $sidebar_layout;
}

/**
 * Get the Blog view style layout.
 *
 * @return string
 */
function silicon_get_blog_view_style_layout() {
	$view_style = get_theme_mod( 'silicon_blog_layout_style', 'default' );
	return $view_style;
}

/**
 * Return if the blog layout has sidebar or not.
 *
 * @return bool
 */
function silicon_has_blog_sidebar() {
	$has_sidebar = false;
	$layout      = silicon_get_blog_layout();

	if ( 'sidebar-left' === $layout || 'sidebar-right' === $layout ) {
		if ( is_active_sidebar( 'sidebar-blog' ) ) {
			$has_sidebar = true;
		}
	}

	return $has_sidebar;
}

/**
 * Displays the class names for the #main element.
 *
 * @since 2.8.0
 *
 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
 */
function silicon_main_class( $class = '' ) {
	// Separates class names with a single space, collates class names for #main element.
	echo 'class="' . esc_attr( implode( ' ', silicon_get_main_class( $class ) ) ) . '"';
}

/**
 * Retrieves an array of the class names for the body element.
 *
 * @global WP_Query $wp_query WordPress Query object.
 *
 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
 * @return string[] Array of class names.
 */
function silicon_get_main_class( $class = '' ) {
	global $wp_query;

	$classes        = array( 'site-main' );
	$sidebar_layout = silicon_get_blog_sidebar_layout();

	if ( is_home() || is_archive() || is_search() ) {
		$blog_has_sidebar = silicon_has_blog_sidebar();
		if ( $blog_has_sidebar && $sidebar_layout ) {
			$classes[] = 'col-xl-9';
			$classes[] = 'col-lg-8';
			$classes[] = 'sidebar-right' === $sidebar_layout ? 'order-first' : 'order-last';
		} else {
			$classes[] = 'col-lg-12';
		}
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the list of CSS body class names for the current post or page.
	 *
	 * @since 2.8.0
	 *
	 * @param string[] $classes An array of body class names.
	 * @param string[] $class   An array of additional class names added to the body.
	 */
	$classes = apply_filters( 'silicon_main_class', $classes, $class );

	return array_unique( $classes );
}
