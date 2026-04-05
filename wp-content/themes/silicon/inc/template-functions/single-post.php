<?php
/**
 * Template functions related to single post.
 *
 * @package silicon
 */

if ( ! function_exists( 'silicon_single_post_header' ) ) {
	/**
	 * Display Single Post Header
	 */
	function silicon_single_post_header() {
		?>
		<section class="pb-3 container">
			<div class="mt-4 pt-lg-2"><?php the_title( '<h1 class="pb-3" style="max-width: 970px;">', '</h1>' ); ?></div>
			<div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between mb-3">
				<div class="d-flex align-items-center flex-wrap text-muted mb-md-0 mb-4 single-post__meta" style="max-width:970px;">
					<?php silicon_single_post_cat(); ?>
					<?php silicon_single_post_date(); ?>
					<?php silicon_single_post_meta(); ?>
				</div>
				<?php silicon_the_post_author( 'single-post' ); ?>
			</div>
		</section>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_post_cat' ) ) {
	/**
	 * Display single post category
	 */
	function silicon_single_post_cat() {
		silicon_the_post_categories( 'single' );
	}
}

if ( ! function_exists( 'silicon_single_post_date' ) ) {
	/**
	 * Display Single post date.
	 */
	function silicon_single_post_date() {
		silicon_the_post_date( 'single', true );
	}
}

if ( ! function_exists( 'silicon_single_post_meta' ) ) {
	/**
	 * Display single post meta.
	 */
	function silicon_single_post_meta() {
		silicon_the_post_meta_icons( 'single' );
	}
}

if ( ! function_exists( 'silicon_single_post_thumbnail' ) ) {
	/**
	 * Displays single post thumbnail using Jarallax.
	 */
	function silicon_single_post_thumbnail() {
		$cover_image_url = apply_filters( 'silicon_cover_image_url', get_the_post_thumbnail_url( null, 'full' ) );
		if ( $cover_image_url ) :
			?>
		<!-- Post image (parallax) -->
		<div class="jarallax mb-lg-5 mb-4" data-jarallax data-speed="0.4" style="height: 36.45vw; min-height: 300px;">
			<div class="jarallax-img" style="background-image: url(<?php echo esc_url( $cover_image_url ); ?>);"></div>
		</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'silicon_single_post_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function silicon_single_post_content() {
		?>
		<div class="entry-content container pt-4 mb-5 pb-2">
			<div class="row gy-4">
				<div class="col-lg-9">
					<?php do_action( 'silicon_post_content_before' ); ?>
					<div class="d-flex flex-column">
						<div class="prose mb-4 pb-2">
							<?php
							the_content(
								sprintf(
									/* translators: %s: post title */
									__( 'Continue reading %s', 'silicon' ),
									'<span class="screen-reader-text">' . get_the_title() . '</span>'
								)
							);
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'silicon' ),
									'after'  => '</div>',
								)
							);
							?>
						</div>
					</div>
					<hr class="mb-4" style="clear: both;">

					<?php do_action( 'silicon_post_content_after' ); ?>
				</div>
			</div>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'silicon_single_post_tags' ) ) {
	/**
	 * Display single post tags.
	 */
	function silicon_single_post_tags() {
		$tags_list = get_the_tag_list();
		if ( $tags_list ) :
			$find    = 'rel="tag">';
			$replace = 'class="btn btn-sm btn-outline-secondary me-2 mb-2" rel="tag">#';
			?>
			<div class="d-flex flex-sm-row flex-column pt-2">
				<h6 class="mt-sm-1 mb-sm-2 mb-3 me-2 text-nowrap"><?php echo esc_html__( 'Related Tags:', 'silicon' ); ?></h6>
				<div>
					<?php echo wp_kses_post( str_replace( $find, $replace, $tags_list ) ); ?>
				</div>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'silicon_single_post_related_posts' ) ) {
	/**
	 * Silicon Related posts static content.
	 */
	function silicon_single_post_related_posts() {

		$posts_content = get_theme_mod( 'single_post_layout', '' );

		if ( silicon_is_mas_static_content_activated() && ! empty( $posts_content ) ) {
			print( silicon_render_content( $posts_content, false ) ); //phpcs:ignore
		}

	}
}
