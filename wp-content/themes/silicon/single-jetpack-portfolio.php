<?php
/**
 * The template for displaying all single posts.
 *
 * @package silicon
 */

get_header();

	$args = array( 'wrap_before' => '<nav aria-label="breadcrumb" class="container py-4 mb-lg-2 mt-lg-3"><ol class="breadcrumb mb-0">' );
	silicon_breadcrumb( $args );

	do_action( 'silicon_single_before_portfolio' );

	the_content();

	do_action( 'silicon_single_after_portfolio' );

get_footer();
