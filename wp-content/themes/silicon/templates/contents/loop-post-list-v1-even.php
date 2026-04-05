<?php
/**
 * Template to display even numbered blog posts in loop with sidebar.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card me-xl-5 mb-4 si-article">
	<div class="card-body">
		<div class="d-flex justify-content-between mb-3">
			<?php silicon_the_post_categories( 'list-v1-even' ); ?>
		</div>
		<?php the_title( sprintf( '<h3 class="h4 entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		<div class="mb-4"><?php the_excerpt(); ?></div>
		<div class="d-flex align-items-center text-muted">
			<?php silicon_the_post_meta( 'post-date', 'list-view-v1' ); ?>
			<?php silicon_the_post_meta( 'post-meta-icons', 'list-view-v1' ); ?>
		</div>
	</div>
</article>
<div class="pb-2 pb-lg-3"></div>
