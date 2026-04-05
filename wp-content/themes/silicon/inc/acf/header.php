<?php
/**
 * Silicon Acf function Header.
 *
 * @package silicon
 */

if ( ! function_exists( 'silicon_acf_is_enable_header' ) ) {
	/**
	 * Enable header.
	 */
	function silicon_acf_is_enable_header() {
		return silicon_get_field( 'custom_header' );
	}
}

if ( ! function_exists( 'silicon_acf_is_enable_sticky' ) ) {
	/**
	 * Enable sticky.
	 */
	function silicon_acf_is_enable_sticky() {
		return silicon_get_field( 'stick_navbar_on_scroll' );
	}
}

if ( ! function_exists( 'silicon_acf_is_enable_position' ) ) {
	/**
	 * Enable position.
	 */
	function silicon_acf_is_enable_position() {
		return silicon_get_field( 'navbar_position' );
	}
}

if ( ! function_exists( 'silicon_acf_is_enable_dark_navbar' ) ) {
	/**
	 * Enable dark navbar.
	 */
	function silicon_acf_is_enable_dark_navbar() {
		return silicon_get_field( 'navbar_text' );
	}
}

if ( ! function_exists( 'silicon_acf_is_enable_light_background' ) ) {
	/**
	 * Enable light background.
	 */
	function silicon_acf_is_enable_light_background() {
		return silicon_get_field( 'background' );
	}
}

if ( ! function_exists( 'silicon_acf_is_enable_shadow' ) ) {
	/**
	 * Enable shadow.
	 */
	function silicon_acf_is_enable_shadow() {
		return silicon_get_field( 'enable_shadow' );
	}
}

if ( ! function_exists( 'silicon_acf_is_disable_dark_mode_shadow' ) ) {
	/**
	 * Enable dark mode shadow.
	 */
	function silicon_acf_is_disable_dark_mode_shadow() {
		return silicon_get_field( 'disable_dark_mode_shadow' );
	}
}

if ( ! function_exists( 'silicon_acf_is_border' ) ) {
	/**
	 * Enable border.
	 */
	function silicon_acf_is_border() {
		return silicon_get_field( 'border' );
	}
}

if ( ! function_exists( 'silicon_acf_is_border_color' ) ) {
	/**
	 * Enable border color.
	 */
	function silicon_acf_is_border_color() {
		return silicon_get_field( 'border_color' );
	}
}

if ( ! function_exists( 'silicon_acf_is_enable_buy_now_button' ) ) {
	/**
	 * Enable buy new button.
	 */
	function silicon_acf_is_enable_buy_now_button() {
		return silicon_get_field( 'enable_buy_now_button' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_icon' ) ) {
	/**
	 * Enable button icon.
	 */
	function silicon_acf_is_buy_now_button_icon() {
		return silicon_get_field( 'buy_now_button_icon' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_color' ) ) {
	/**
	 * Enable button color.
	 */
	function silicon_acf_is_buy_now_button_color() {
		return silicon_get_field( 'buy_now_button_color' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_text' ) ) {
	/**
	 * Enable button text.
	 */
	function silicon_acf_is_buy_now_button_text() {
		return silicon_get_field( 'buy_now_button_text' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_link' ) ) {
	/**
	 * Enable button link.
	 */
	function silicon_acf_is_buy_now_button_link() {
		return silicon_get_field( 'buy_now_button_link' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_size' ) ) {
	/**
	 * Enable button size.
	 */
	function silicon_acf_is_buy_now_button_size() {
		return silicon_get_field( 'buy_now_button_size' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_shape' ) ) {
	/**
	 * Enable button shape.
	 */
	function silicon_acf_is_buy_now_button_shape() {
		return silicon_get_field( 'buy_now_button_shape' );
	}
}

if ( ! function_exists( 'silicon_acf_is_buy_now_button_css' ) ) {
	/**
	 * Enable button css.
	 */
	function silicon_acf_is_buy_now_button_css() {
		return silicon_get_field( 'buy_now_button_css' );
	}
}
