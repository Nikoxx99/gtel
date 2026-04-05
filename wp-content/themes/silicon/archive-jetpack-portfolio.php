<?php
/**
 * The template for displaying the Portfolio archive page.
 *
 * @package silicon
 */

get_header();

do_action( 'silicon_before_portfolio' );

if ( have_posts() ) {

	get_template_part( 'loop', 'portfolio' );

} else {

	get_template_part( 'templates/contents/content', 'none' );

}

do_action( 'silicon_after_portfolio' );

get_footer();
