<?php
/**
 * Template for loop post card list.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card border-0 shadow-sm overflow-hidden">
	<div class="row g-0 position-relative">
		<div class="col-sm-5">
			<?php silicon_the_post_thumbnail( 'full', '', 460, 361 ); ?>
		</div>
		<div class="col-sm-7">
			<div class="card-body">
				<div class="d-flex align-items-center mb-3">
					<?php silicon_the_post_categories( 'card-list' ); ?>
					<?php silicon_the_post_date( 'card-list' ); ?>
				</div>
				<h3 class="h5">
					<a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a>
				</h3>
				<hr class="my-4 bg-current">
				<div class="d-flex flex-sm-nowrap flex-wrap align-items-center justify-content-between">
					<?php silicon_the_post_author( 'card-list', $args ); ?>
					<?php silicon_the_post_meta_icons( 'card-list' ); ?>
				</div>
			</div>
		</div>
	</div>
</article>
