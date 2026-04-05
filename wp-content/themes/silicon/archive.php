<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package silicon
 */

get_header();
silicon_breadcrumb();
?>
<div class="container">
	<div id="primary" class="content-area mt-4 mb-lg-5 pt-lg-2 pb-5">
		<header class="page-header">
			<div class="d-flex align-items-center justify-content-between mb-4 pb-1 pb-md-3">
				<?php the_archive_title( '<h1 class="page-title mb-0">', '</h1>' ); ?>
				<?php do_action( 'silicon_page_header' ); ?>
			</div>
		</header><!-- .page-header -->
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

		</div>
	</div><!-- #primary -->
</div>
<?php
get_footer();
