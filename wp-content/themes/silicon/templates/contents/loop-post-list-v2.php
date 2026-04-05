<?php
/**
 * Template for loop post list view with no sidebar.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$card_body_class = 'col-sm-8';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'card', 'border-0', 'shadow-sm', 'overflow-hidden', 'mb-4', 'si-article' ) ); ?>>
	<div class="row g-0 position-relative">
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="col-sm-4">
			<?php silicon_the_post_thumbnail( 'full', '', 104, 75 ); ?>
			<?php do_action( 'silicon_blog_actions' ); ?>
		</div>
		<?php else : ?>
			<?php $card_body_class = 'col-sm-12'; ?>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $card_body_class ); ?>">
			<div class="card-body">
				<div class="d-flex align-items-center mb-3">
					<?php silicon_the_post_categories( 'list-v2' ); ?>
					<?php silicon_the_post_meta( 'post-date', 'list-view' ); ?>
				</div>
				<?php the_title( sprintf( '<h3 class="h4 entry-title"><a href="%s" class="stretched-link" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
				<?php the_excerpt(); ?>
				<hr class="my-4 bg-current">
				<div class="d-flex align-items-center justify-content-between">
					<?php silicon_the_post_meta( 'post-author', 'list-view' ); ?>
					<?php silicon_the_post_meta( 'post-meta-icons', 'list-view' ); ?>
				</div>
			</div>
		</div>
	</div>
</article>
