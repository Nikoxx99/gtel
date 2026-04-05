<?php
/**
 * Template for Blog posts Grid view v1.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$card_class      = 'card position-relative';
$card_body_class = 'card-body';

if ( has_post_thumbnail() ) {
	$card_class      .= ' border-0 bg-transparent';
	$card_body_class .= ' pb-1 px-0';
}

?>
<div class="masonry-grid-item col pb-2 pb-lg-3 si-article">
	<article class="<?php echo esc_attr( $card_class ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="overflow-hidden rounded-3">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $card_body_class ); ?>">
			<div class="d-flex justify-content-between mb-3">
				<?php silicon_the_post_categories( 'grid-v1' ); ?>
			</div>
			<?php the_title( sprintf( '<h3 class="h4 entry-title"><a href="%s" class="stretched-link" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			<div class="mb-4"><?php the_excerpt(); ?></div>
			<div class="d-flex align-items-center text-muted">
				<?php silicon_the_post_meta( 'post-date', 'grid-view-v1' ); ?>
				<?php silicon_the_post_meta( 'post-meta-icons', 'grid-view-v1' ); ?>
			</div>
		</div>
	</article>
</div>
