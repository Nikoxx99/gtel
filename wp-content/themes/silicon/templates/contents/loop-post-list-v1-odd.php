<?php
/**
 * Template to display odd numbered blog posts in loop with sidebar.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card border-0 bg-transparent me-xl-5 mb-4 si-article">
	<div class="row g-0">
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="col-sm-5 position-relative bg-position-center bg-repeat-0 bg-size-cover rounded-3" style="background-image: url(<?php the_post_thumbnail_url( 'full' ); ?>); min-height: 15rem;">
		</div>
		<?php endif; ?>
		<div class="col-sm-7">
			<div class="card-body px-0 pt-sm-0 ps-sm-4 pb-0 pb-sm-4">
				<?php silicon_the_post_categories( 'list-v1-odd' ); ?>
				<?php the_title( sprintf( '<h3 class="h4 entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
				<div class="mb-4"><?php the_excerpt(); ?></div>
				<div class="d-flex align-items-center text-muted">
					<?php silicon_the_post_meta( 'post-date', 'list-view-v1-odd' ); ?>
					<?php silicon_the_post_meta( 'post-meta-icons', 'list-view-v1' ); ?>
				</div>
			</div>
		</div>
	</div>
</article>
<div class="pb-2 pb-lg-3"></div>
