<?php
/**
 * Template for Blog posts Grid v2.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="card border-0 shadow-sm h-100">
	<div class="position-relative card-img-top">
		<?php silicon_the_post_thumbnail( 'full', '', 104, 75, 'card-img-top overflow-hidden' ); ?>
	</div>
	<div class="card-body pb-4">
		<div class="d-flex align-items-center justify-content-between mb-3">
			<?php silicon_the_post_categories( 'grid-v2' ); ?>
			<?php silicon_the_post_meta( 'post-date', 'grid-v2' ); ?>
		</div>
		<h3 class="h5 mb-0">
			<a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a>
		</h3>
	</div>
	<div class="card-footer py-4">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="d-flex align-items-center fw-bold text-dark text-decoration-none">
			<?php
				echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array( 'class' => 'rounded-circle me-3' ) );
				echo esc_html( get_the_author() );
			?>
		</a>
	</div>
</article>
