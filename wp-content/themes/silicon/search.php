<?php
/**
 * The template for displaying search results pages.
 *
 * @package silicon
 */

get_header();
silicon_breadcrumb();
?>
<div class="container">
	<div id="primary" class="content-area mt-4 mb-lg-5 pt-lg-2 pb-5">
		<header class="page-header mb-4 pb-1 pb-md-3">
			<h1 class="page-title">
				<?php
					/* translators: %s: search term */
					printf( esc_attr__( 'Search Results for: %s', 'silicon' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
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
