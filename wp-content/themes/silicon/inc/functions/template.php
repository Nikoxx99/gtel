<?php
/**
 * Functions for the templating systems.
 *
 * @package silicon
 */

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function silicon_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	global $silicon_version;
	$cache_key = sanitize_key( implode( '-', array( 'template', $template_name, $template_path, $default_path, $silicon_version ) ) );
	$template  = (string) wp_cache_get( $cache_key, 'silicon' );

	if ( ! $template ) {
		$template = silicon_locate_template( $template_name, $template_path, $default_path );

		// Don't cache the absolute path so that it can be shared between web servers with different paths.
		$cache_path = silicon_tokenize_path( $template, silicon_get_path_define_tokens() );

		silicon_set_template_cache( $cache_key, $cache_path );
	} else {
		// Make sure that the absolute path to the template is resolved.
		$template = silicon_untokenize_path( $template, silicon_get_path_define_tokens() );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'silicon_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( '%s does not exist.', 'silicon' ), '<code>' . esc_html( $filter_template ) . '</code>' ), '2.1' );
			return;
		}
		$template = $filter_template;
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	);

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				esc_html__( 'action_args should not be overwritten when calling silicon_get_template.', 'silicon' ),
				'3.6.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args ); // @codingStandardsIgnoreLine
	}

	do_action( 'silicon_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'silicon_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 * @return string
 */
function silicon_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = 'templates/';
	}

	if ( ! $default_path ) {
		$default_path = 'templates/';
	}

	// Look within passed path within the theme - this is priority.
	if ( false !== strpos( $template_name, 'product_cat' ) || false !== strpos( $template_name, 'product_tag' ) ) {
		$cs_template = str_replace( '_', '-', $template_name );
		$template    = locate_template(
			array(
				trailingslashit( $template_path ) . $cs_template,
				$cs_template,
			)
		);
	}

	if ( empty( $template ) ) {
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
	}

	// Get default template/.
	if ( ! $template ) {
		if ( empty( $cs_template ) ) {
			$template = $default_path . $template_name;
		} else {
			$template = $default_path . $cs_template;
		}
	}

	// Return what we found.
	return apply_filters( 'silicon_locate_template', $template, $template_name, $template_path );
}

/**
 * Given a path, this will convert any of the subpaths into their corresponding tokens.
 *
 * @param string $path The absolute path to tokenize.
 * @param array  $path_tokens An array keyed with the token, containing paths that should be replaced.
 * @return string The tokenized path.
 */
function silicon_tokenize_path( $path, $path_tokens ) {
	// Order most to least specific so that the token can encompass as much of the path as possible.
	uasort(
		$path_tokens,
		function ( $a, $b ) {
			$a = strlen( $a );
			$b = strlen( $b );

			if ( $a > $b ) {
				return -1;
			}

			if ( $b > $a ) {
				return 1;
			}

			return 0;
		}
	);

	foreach ( $path_tokens as $token => $token_path ) {
		if ( 0 !== strpos( $path, $token_path ) ) {
			continue;
		}

		$path = str_replace( $token_path, '{{' . $token . '}}', $path );
	}

	return $path;
}

/**
 * Given a tokenized path, this will expand the tokens to their full path.
 *
 * @param string $path The absolute path to expand.
 * @param array  $path_tokens An array keyed with the token, containing paths that should be expanded.
 * @return string The absolute path.
 */
function silicon_untokenize_path( $path, $path_tokens ) {
	foreach ( $path_tokens as $token => $token_path ) {
		$path = str_replace( '{{' . $token . '}}', $token_path, $path );
	}

	return $path;
}

/**
 * Fetches an array containing all of the configurable path constants to be used in tokenization.
 *
 * @return array The key is the define and the path is the constant.
 */
function silicon_get_path_define_tokens() {
	$defines = array(
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
		'WPMU_PLUGIN_DIR',
		// 'PLUGINDIR',
		'WP_THEME_DIR',
	);

	$path_tokens = array();
	foreach ( $defines as $define ) {
		if ( defined( $define ) ) {
			$path_tokens[ $define ] = constant( $define );
		}
	}

	return apply_filters( 'silicon_get_path_define_tokens', $path_tokens );
}

/**
 * Add a template to the template cache.
 *
 * @param string $cache_key Object cache key.
 * @param string $template Located template.
 */
function silicon_set_template_cache( $cache_key, $template ) {
	wp_cache_set( $cache_key, $template, 'silicon' );

	$cached_templates = wp_cache_get( 'cached_templates', 'silicon' );
	if ( is_array( $cached_templates ) ) {
		$cached_templates[] = $cache_key;
	} else {
		$cached_templates = array( $cache_key );
	}

	wp_cache_set( 'cached_templates', $cached_templates, 'silicon' );
}

/**
 * Clear the template cache.
 */
function silicon_clear_template_cache() {
	$cached_templates = wp_cache_get( 'cached_templates', 'silicon' );
	if ( is_array( $cached_templates ) ) {
		foreach ( $cached_templates as $cache_key ) {
			wp_cache_delete( $cache_key, 'silicon' );
		}

		wp_cache_delete( 'cached_templates', 'silicon' );
	}
}

/**
 * Bootstrap pagination.
 *
 * @param \WP_Query $wp_query The WordPress query.
 * @param bool      $echo     Should return or print.
 * @param string    $ul_class The <ul> element class.
 */
function silicon_bootstrap_pagination( \WP_Query $wp_query = null, $echo = true, $ul_class = '' ) {

	if ( null === $wp_query ) {
		global $wp_query;
	}

	$args = array(
		'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
		'format'       => '?paged=%#%',
		'current'      => max( 1, get_query_var( 'paged' ) ),
		'total'        => $wp_query->max_num_pages,
		'type'         => 'array',
		'show_all'     => false,
		'end_size'     => 3,
		'mid_size'     => 1,
		'prev_next'    => true,
		'prev_text'    => '<i class="bx bx-chevron-left mx-n1"></i>',
		'next_text'    => '<i class="bx bx-chevron-right mx-n1"></i>',
		'add_args'     => false,
		'add_fragment' => '',
		'ul_class'     => $ul_class,
	);

	$pages      = paginate_links( $args );
	$pagination = silicon_print_pagination_links( $pages, $args );

	if ( ! empty( $pagination ) ) {
		if ( $echo ) {
			echo wp_kses_post( $pagination );
		} else {
			return $pagination;
		}
	}

	return null;
}

/**
 * Print pagination links.
 *
 * @param array $pages Page Links.
 * @param array $args  The arguments for generating the pagination links.
 * @return string|null
 */
function silicon_print_pagination_links( $pages, $args ) {

	$pagination  = null;
	$ul_class    = isset( $args['ul_class'] ) ? $args['ul_class'] : '';
	$total_pages = $args['total'];

	if ( is_array( $pages ) ) {

		if ( ! empty( $ul_class ) ) {
			$ul_class = ' ' . $ul_class;
		}
		if ( isset( $args['pagination_type'] ) && 'numbers_and_prev_next' === $args['pagination_type'] ) {
			$current_page_class = ' d-none d-sm-block ';
			$fraction_class     = ' d-sm-none';
		} else {
			$current_page_class = ' ';
			$fraction_class     = ' d-none';
		}

		$pagination = '<nav aria-label="' . esc_attr__( 'Page navigation', 'silicon' ) . '"><ul class="pagination' . esc_attr( $ul_class ) . '">';
		$paged      = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

		/* translators: %1$s - Current Page, %2$s - Total Pages */
		$paged_string = sprintf( esc_html__( '%1$s / %2$s', 'silicon' ), $paged, $total_pages );

		foreach ( $pages as $page ) {
			if ( strpos( $page, 'prev' ) || strpos( $page, 'next' ) ) {
				$pagination .= '<li class="page-item">' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
			} else {
				$pagination .= '<li class="page-item'. $current_page_class . ( strpos( $page, 'current' ) !== false ? 'active' : '' ) . '">' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
			}

			if ( strpos( $page, 'prev' ) ) {
				$pagination .= '<li class="page-item disabled' . $fraction_class . '"><span class="page-link text-body">' . $paged_string . '</span></li>';
			}
		}

		$pagination .= '</ul></nav>';
	}

	return $pagination;
}
