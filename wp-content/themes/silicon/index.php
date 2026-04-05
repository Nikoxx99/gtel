<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package silicon
 */

get_header();
?>
<div <?php silicon_content_container_class( 'pt-4 mt-lg-3' ); ?>>
	<div id="primary" class="content-area mt-4 mb-lg-5 pt-lg-2 pb-5">
		<div class="row">
			<main id="main" role="main" <?php silicon_main_class(); ?>>

			<?php
			if ( have_posts() ) :

				get_template_part( 'loop' );

			else :

				get_template_part( 'content', 'none' );

			endif;
			?>

			</main><!-- #main -->

			<?php do_action( 'silicon_sidebar' ); ?>

		</div><!-- /.row -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
