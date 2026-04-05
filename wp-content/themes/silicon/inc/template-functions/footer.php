<?php
/**
 * Template functions for Footer
 */

if ( ! function_exists( 'silicon_footer_static' ) ) {
	/**
	 * Silicon footer static..
	 */
	function silicon_footer_static() {
		$page_variant = get_theme_mod( '404_version', 'v1' );
		if ( is_404() && 'v1' === $page_variant ) {
				get_template_part( 'templates/global/footer', '404' );
		} else {

			$sn_page_options   = array();
			$enable_acf_footer = silicon_get_field( 'silicon_enable_footer' );

			if ( function_exists( 'silicon_option_enabled_post_types' ) && is_singular( silicon_option_enabled_post_types() ) ) {
				$clean_meta_data  = get_post_meta( get_the_ID(), '_sn_page_options', true );
				$_sn_page_options = maybe_unserialize( $clean_meta_data );

				if ( is_array( $_sn_page_options ) ) {
					$sn_page_options = $_sn_page_options;
				}
			}

			if ( silicon_has_custom_footer( $sn_page_options ) ) {

				$footer_content = isset( $sn_page_options['footer']['silicon_static_widgets'] ) ? $sn_page_options['footer']['silicon_static_widgets'] : '';
				$footer_variant = isset( $sn_page_options['footer']['silicon_footer_variant'] ) ? $sn_page_options['footer']['silicon_footer_variant'] : '';
			} elseif ( true === $enable_acf_footer ) {

				$footer_content = silicon_get_field( 'silicon_static_footers' )[0];
				$footer_variant = silicon_get_field( 'silicon_footer_variant' );
			} else {

				$footer_content = get_theme_mod( 'footer_static_content', '' );
				$footer_variant = get_theme_mod( 'footer_silicon_footer_variant', 'none' );
			}

			if ( silicon_is_mas_static_content_activated() && ! empty( $footer_content ) && 'static-content' === $footer_variant ) {
				print( silicon_render_content( $footer_content, false ) ); //phpcs:ignore
			}

			if ( 'none' === $footer_variant ) {
				get_template_part( 'templates/global/footer', 'v1' );
			}
		}
	}
}

/**
 * Checks if a page has custom footer or not in Elementor.
 *
 * @param array $options Page meta options.
 */
function silicon_has_custom_footer( $options ) {
	$has_custom_footer = false;

	if ( isset( $options['footer']['silicon_enable_custom_footer'] ) && 'yes' === $options['footer']['silicon_enable_custom_footer'] ) {
		$has_custom_footer = true;
	}

	return $has_custom_footer;
}

/**
 * Template functions for Footer CopyRights
 */

if ( ! function_exists( 'silicon_footer_copyright_text' ) ) {
	/**
	 * Silicon footer copyright text..
	 */
	function silicon_footer_copyright_text() {
		$enable_acf_footer = silicon_get_field( 'silicon_enable_footer' );
		$default         = wp_kses_post( __( '© All rights reserved. Made with <i class="bx bx-heart d-inline-block fs-lg text-gradient-primary align-middle mt-n1 mx-1"></i> by&nbsp; <a href="https://madrasthemes.com/" class="text-muted" target="_blank" rel="noopener">MadrasThemes</a>', 'silicon' ) );

		$sn_page_options   = array();
		if ( function_exists( 'silicon_option_enabled_post_types' ) && is_singular( silicon_option_enabled_post_types() ) ) {
			$clean_meta_data  = get_post_meta( get_the_ID(), '_sn_page_options', true );
			$_sn_page_options = maybe_unserialize( $clean_meta_data );

			if ( is_array( $_sn_page_options ) ) {
				$sn_page_options = $_sn_page_options;
			}
		}

		if ( silicon_has_custom_footer( $sn_page_options ) ) {
			$copyright_text  = isset( $sn_page_options['footer']['silicon_copyright_text'] ) ? $sn_page_options['footer']['silicon_copyright_text'] : '';
		} elseif ( true === $enable_acf_footer ) {
			$copyright_text  = silicon_get_field( 'silicon_copyright_text' );
		} else {
			$copyright_text  = get_theme_mod( 'footer_silicon_copyright_text', $default );
		}
		return $copyright_text;
	}
}
