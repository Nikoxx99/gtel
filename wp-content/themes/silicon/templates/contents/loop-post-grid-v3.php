<?php
/**
 * Template for Blog posts Grid v3.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card p-md-3 p-2 border-0 shadow-sm card-hover-primary h-100 mx-2">
	<div class="card-body pb-0">
		<div class="d-flex align-items-center justify-content-between mb-3">
			<?php silicon_the_post_categories( 'grid-v3' ); ?>
			<?php silicon_the_post_meta( 'post-date', 'grid-v3' ); ?>
		</div>
		<h3 class="h4">
			<a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a>
		</h3>
		<div class="mb-0-last-child"><?php the_excerpt(); ?></div>
	</div>
	<div class="card-footer d-flex align-items-center py-4 text-muted border-top-0">
		<?php silicon_the_post_meta( 'post-meta-icons', 'grid-v3' ); ?>
	</div>
</article>
