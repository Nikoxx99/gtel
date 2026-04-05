<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package silicon
 */

get_header();

$masthead_class  = silicon_get_masthead_class();
$should_offset   = in_array( 'top-[32px]', $masthead_class, true );
$primary_classes = 'content-area';

if ( $should_offset ) {
	$primary_classes .= ' pt-[76px]';
}

?>
<div id="primary" class="<?php echo esc_attr( $primary_classes ); ?>">
	<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'silicon_page_before' );

			get_template_part( 'content', 'page' );

			do_action( 'silicon_page_after' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->
<?php

get_footer();
