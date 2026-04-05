<?php
/**
 * Template for Blog posts grid for podcast
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<article class="h-auto pb-3 position-relative">
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="d-block position-relative rounded-3 mb-3">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array( 'class' => 'position-absolute top-0 start-0 rounded-circle zindex-2 mt-3 ms-3' ) ); ?>
		<?php silicon_the_post_duration(); ?>
		<?php the_post_thumbnail( 'full', array( 'class' => 'rounded-3' ) ); ?>
		<a href="<?php the_permalink(); ?>" class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-35 rounded-3" aria-label="<?php echo esc_attr__( 'Listen podcast', 'silicon' ); ?>"></a>
	</div>
	<?php endif; ?>
	<div class="d-flex align-items-center mb-2">
		<?php silicon_the_post_categories( 'grid-podcast' ); ?>
		<?php silicon_the_post_date( 'grid-podcast', true ); ?>
	</div>
	<h3 class="h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<?php silicon_the_post_meta_icons( 'grid-podcast' ); ?>
	<a href="<?php the_permalink(); ?>" class="btn btn-link px-0 mt-3 stretched-link">
		<i class="bx bx-play-circle fs-lg me-2"></i>
		<?php echo esc_html__( 'Listen now', 'silicon' ); ?>
	</a>
</article>
