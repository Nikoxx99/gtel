<?php
/**
 * Template for Blog posts default view v1.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$card_class      = 'card position-relative';
$card_body_class = 'card-body mb-0-last-child';
$aspect_ratio    = true;
$featured        = '';

if ( has_post_thumbnail() ) {
	$card_class       .= ' border-0 bg-transparent';
	$card_body_class  .= ' pb-1 px-0';
	$post_thumbnail_id = get_post_thumbnail_id();
	$thumbnail_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
	if ( ! empty( $thumbnail_image[1] ) && 420 < $thumbnail_image[1] ) {
		$aspect_ratio = false;
	}
}

if ( is_sticky() ) {
	$featured = '<span class="badge fs-sm ms-2 text-white bg-info position-relative" style="top:-2px">' . esc_html__( 'Featured', 'silicon' ) . '</span>';
}

?>
<div class="masonry-grid-item col pb-2 pb-lg-3">
	<article class="<?php echo esc_attr( $card_class ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="overflow-hidden position-relative rounded-3">
			<?php
			if ( $aspect_ratio ) {
				silicon_the_post_thumbnail( 'full', '', 104, 75, 'rounded-3 overflow-hidden' );
			} else {
				the_post_thumbnail( 'full' );
			}
			?>
		</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $card_body_class ); ?>">
			<?php silicon_the_post_categories(); ?>
			<?php the_title( sprintf( '<h3 class="h4 entry-title"><a href="%s" class="stretched-link" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' . $featured . '</h3>' ); ?>
			<div class="mb-0-last-child"><?php the_excerpt(); ?></div>
			<?php if ( 'post' === get_post_type() ) : ?>
			<div class="mt-4 d-flex align-items-center text-muted post__meta">
				<?php silicon_the_post_meta( 'post-date', 'default' ); ?>
				<?php silicon_the_post_meta( 'post-meta-icons', 'grid-view-v1' ); ?>
			</div>
			<?php endif; ?>
		</div>
	</article>
</div>
