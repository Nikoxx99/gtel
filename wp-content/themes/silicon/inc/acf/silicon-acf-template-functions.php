<?php
/**
 * Template functions related to ACF.
 */

if ( ! function_exists( 'silicon_acf_cover_image_url' ) ) {
	/**
	 * Get the cover image URL.
	 *
	 * @param string $url The URL of the image that needs to be displayed.
	 * @return string
	 */
	function silicon_acf_cover_image_url( $url ) {
		$cover_image_url = get_field( 'cover_image' );
		if ( $cover_image_url ) {
			$url = $cover_image_url;
		}
		return $url;
	}
}

if ( ! function_exists( 'silicon_acf_main_category' ) ) {
	/**
	 * Replace the category list with just one main category.
	 *
	 * @param string $category_list Category list for a post.
	 * @return string
	 */
	function silicon_acf_main_category( $category_list ) {
		global $wp_rewrite;
		$term = get_field( 'main_category' );
		if ( $term ) {
			$rel           = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
			$category_list = '<a href="' . esc_url( get_term_link( $term ) ) . '"  ' . $rel . '>' . $term->name . '</a>';
		}
		return $category_list;
	}
}

if ( ! function_exists( 'silicon_acf_main_cat_bg' ) ) {
	/**
	 * Replace the background of the main category.
	 *
	 * @param string $bg Current background.
	 * @return string
	 */
	function silicon_acf_main_cat_bg( $bg ) {
		$main_cat_bg = get_field( 'main_category_bg' );
		if ( $main_cat_bg ) {
			$bg = $main_cat_bg;
		}
		return $bg;
	}
}

if ( ! function_exists( 'silicon_acf_single_podcast_duration' ) ) {
	/**
	 * Replace the background of the main category.
	 *
	 * @return string
	 */
	function silicon_acf_single_podcast_duration() {
		$duration = silicon_get_field( 'duration' );
		return $duration;
	}
}
