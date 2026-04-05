<?php
/**
 * Template functions related to the page.
 */

if ( ! function_exists( 'silicon_page_header' ) ) {
	/**
	 * Display the page header
	 *
	 * @since 1.0.0
	 */
	function silicon_page_header() {
		if ( is_front_page() && is_page_template( 'template-fullwidth.php' ) ) {
			return;
		}

		?>
		<header class="entry-header">
			<?php if ( has_post_thumbnail() ) : ?>
			<div class="jarallax d-none d-md-block" data-jarallax data-speed="0.4">
				<span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary-translucent"></span>
				<div class="jarallax-img" style="background-image: url(<?php the_post_thumbnail_url( 'full' ); ?>);"></div>
				<div class="d-none d-xxl-block" style="height: 700px;"></div>
				<div class="d-none d-md-block d-xxl-none" style="height: 550px;"></div>
			</div>
			<?php endif; ?>
			<?php silicon_breadcrumb(); ?>
			<div class="container">
				<?php
				the_title( '<h1 class="entry-title pt-4 mt-lg-3 pb-3 pb-lg-4">', '</h1>' );
				?>
			</div>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'silicon_page_content' ) ) {
	/**
	 * Display the post content
	 *
	 * @since 1.0.0
	 */
	function silicon_page_content() {
		?>
		<div class="d-flex flex-column">
			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->
			<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-links my-3">' . esc_html__( 'Pages:', 'silicon' ),
						'after'  => '</div>',
					)
				);
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'silicon_page_comments' ) ) {
	/**
	 * Display page comments.
	 */
	function silicon_page_comments() {
		?>
		<div class="row">
			<div class="col-lg-9">
				<?php silicon_display_comments(); ?>
			</div>
		</div>
		<?php
	}
}
