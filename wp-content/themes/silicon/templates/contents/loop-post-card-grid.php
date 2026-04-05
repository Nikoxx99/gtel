<?php
/**
 * Template for Loop Post Card Grid.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card h-100 border-0 shadow-sm">
	<?php silicon_the_post_thumbnail( 'full', '', 263, 132, 'position-relative card-img-top overflow-hidden' ); ?>
	<div class="card-body pb-4">
		<div class="d-flex align-items-center justify-content-between mb-3">
			<?php silicon_the_post_categories( 'card-grid' ); ?>
			<?php silicon_the_post_date( 'card-grid', true ); ?>
		</div>
		<h3 class="h5 mb-0"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	</div>
	<div class="card-footer py-4">
		<?php silicon_the_post_author( 'card-grid', $args ); ?>
	</div>
</article>
