<?php
/**
 * Template for Blog post in slider article.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card h-100 border-0 shadow-sm card-hover-primary">
	<div class="card-body pb-0">
		<div class="d-flex align-items-center justify-content-between mb-3">
			<?php silicon_the_post_categories( 'slider-article' ); ?>
			<?php silicon_the_post_date( 'slider-article', true ); ?>
		</div>
		<h3 class="h5 mb-0"><a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a></h3>
	</div>
	<div class="card-footer d-flex align-items-center py-4 text-muted border-top-0">
		<?php silicon_the_post_meta_icons( 'slider-article' ); ?>
	</div>
</article>
