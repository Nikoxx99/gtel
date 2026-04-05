<?php
/**
 * The template for displaying all single posts.
 *
 * @package silicon
 */

get_header();

silicon_breadcrumb();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'silicon_single_post_before' );

			if ( 'audio' === get_post_format() ) {
				get_template_part( 'content', 'single-audio' );
			} else {
				get_template_part( 'content', 'single' );
			}

			do_action( 'silicon_single_post_after' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
