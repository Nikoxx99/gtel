<?php
/**
 * Silicon Theme Customizer
 *
 * @package Silicon
 */

if ( ! function_exists( 'silicon_sass_hex_to_rgba' ) ) {
	function silicon_sass_hex_to_rgba( $hex, $alpa = '' ) {
		$hex = sanitize_hex_color( $hex );
		preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $matches );
		for ( $i = 1; $i <= 3; $i++ ) {
			$matches[ $i ] = hexdec( $matches[ $i ] );
		}
		if ( ! empty( $alpa ) ) {
			$rgb = 'rgba(' . $matches[1] . ', ' . $matches[2] . ', ' . $matches[3] . ', ' . $alpa . ')';
		} else {
			$rgb = 'rgba(' . $matches[1] . ', ' . $matches[2] . ', ' . $matches[3] . ')';
		}
		return $rgb;
	}
}

if ( ! function_exists( 'silicon_sass_yiq' ) ) {
	function silicon_sass_yiq( $hex ) {
		$hex    = sanitize_hex_color( $hex );
		$length = strlen( $hex );
		if ( $length < 5 ) {
			$hex = ltrim( $hex, '#' );
			$hex = '#' . $hex . $hex;
		}

		preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $matches );

		for ( $i = 1; $i <= 3; $i++ ) {
			$matches[ $i ] = hexdec( $matches[ $i ] );
		}
		$yiq = ( ( $matches[1] * 299 ) + ( $matches[2] * 587 ) + ( $matches[3] * 114 ) ) / 1000;
		return ( $yiq >= 128 ) ? '#000' : '#fff';
	}
}

/**
 * Get all of the silicon theme colors.
 *
 * @return array $silicon_theme_colors The silicon Theme Colors.
 */
if ( ! function_exists( 'silicon_get_theme_colors' ) ) {
	function silicon_get_theme_colors() {
		$silicon_theme_colors = array(
			'primary_color' => get_theme_mod( 'silicon_primary_color', apply_filters( 'silicon_default_primary_color', '#6366f1' ) ),
		);

		return apply_filters( 'silicon_get_theme_colors', $silicon_theme_colors );
	}
}

/**
 * Get Customizer Color css.
 *
 * @see silicon_get_custom_color_css()
 * @return array $styles the css
 */
if ( ! function_exists( 'silicon_get_custom_color_css' ) ) {
	function silicon_get_custom_color_css() {
		$silicon_theme_colors      = silicon_get_theme_colors();
		$primary_color             = $silicon_theme_colors['primary_color'];
		$primary_color_yiq         = silicon_sass_yiq( $primary_color );
		$primary_color_darken_10p  = silicon_adjust_color_brightness( $primary_color, -4.5 );
		$primary_color_darken_15p  = silicon_adjust_color_brightness( $primary_color, -5.7 );
		$primary_color_lighten_20p = silicon_adjust_color_brightness( $primary_color, 20 );
		$primary_color_lighten_10p = silicon_adjust_color_brightness( $primary_color, 27.7 );


		$styles =
		'
/*
 * Primary Color
 */


';

		return apply_filters( 'silicon_get_custom_color_css', $styles );
	}
}


/**
 * Add CSS in <head> for styles handled by the theme customizer
 *
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'silicon_enqueue_custom_color_css' ) ) {
	function silicon_enqueue_custom_color_css() {
		if ( get_theme_mod( 'silicon_enable_custom_color', 'no' ) === 'yes' ) {
			$silicon_theme_colors      = silicon_get_theme_colors();

			$primary_color             = $silicon_theme_colors['primary_color'];
			$primary_color_yiq         = silicon_sass_yiq( $primary_color );
			$primary_color_darken_10p  = silicon_adjust_color_brightness( $primary_color, -18 );
			$primary_color_darken_15p  = silicon_adjust_color_brightness( $primary_color, -5.7 );
			$primary_color_darken_45p  = silicon_adjust_color_brightness( $primary_color, -45 );
			$primary_color_soft_10     = silicon_sass_hex_to_rgba( $primary_color, '.1' );
			$primary_color_faded       = silicon_sass_hex_to_rgba( $primary_color, '.12' );
			$primary_color_shadow      = silicon_sass_hex_to_rgba( $primary_color, '.9' );
			$primary_color_shadow_sm   = silicon_sass_hex_to_rgba( $primary_color, '.2' );
			$primary_color_border      = silicon_sass_hex_to_rgba( $primary_color, '.35' );
			$primary_color_soft_10d    = silicon_sass_hex_to_rgba( $primary_color, '.12' );
			$primary_color_desat       = silicon_sass_hex_to_rgba( $primary_color, '.6' );
			$primary_color_opacity     = silicon_sass_hex_to_rgba( $primary_color, '.05' );
			$primary_color_outline_20  = silicon_sass_hex_to_rgba( $primary_color, '.2' );
			$primary_color_bg_outline  = silicon_sass_hex_to_rgba( $primary_color, '.08' );
			$primary_color_outline_70  = silicon_sass_hex_to_rgba( $primary_color, '.7' );
			$primary_color_outline_5   = silicon_sass_hex_to_rgba( $primary_color, '.5' );
			$primary_color_opacity_15  = silicon_sass_hex_to_rgba( $primary_color, '.20' );
			$primary_color_opacity_8   = silicon_sass_hex_to_rgba( $primary_color, '.8' );

			$color_root = ':root {
				--sl-primary: 				' . $silicon_theme_colors['primary_color'] . ';
				--sl-primary-shadow:		' . $primary_color_shadow . ';
				--sl-primary-shadow-sm:		' . $primary_color_shadow_sm . ';
				--sl-primary-faded:		    ' . $primary_color_faded . ';
				--sl-primary-border:		' . $primary_color_border . ';
				--sl-primary-bg-d: 			' . $primary_color_darken_10p . ';
				--sl-primary-border-d: 		' . $primary_color_darken_15p . ';
				--sl-primary-soft: 			' . $primary_color_soft_10 . ';
				--sl-primary-soft-d: 		' . $primary_color_soft_10d . ';
				--sl-primary-desat: 		' . $primary_color_desat . ';
				--sl-primary-o-5: 			' . $primary_color_opacity . ';
				--sl-primary-outline-20: 	' . $primary_color_outline_20 . ';
				--sl-primary-outline-bg: 	' . $primary_color_bg_outline . ';
				--sl-dark-primary: 			' . $primary_color_darken_45p . ';
				--sl-primary-outline-5: 	' . $primary_color_outline_5 .';
				--sl-primary-outline-75: 	' . $primary_color_outline_70 . ';
				--sl-primary-opacity-15: 	' . $primary_color_opacity_15 . ';
				--sl-primary-opacity-8: 	' . $primary_color_opacity_8 . ';
			}';
			$styles     = $color_root . silicon_get_custom_color_css();

			wp_add_inline_style( 'silicon-color', $styles );
		}
	}
}


add_action( 'wp_enqueue_scripts', 'silicon_enqueue_custom_color_css', 130 );
