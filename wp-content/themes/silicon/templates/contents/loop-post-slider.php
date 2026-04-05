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
<article class="card h-100 position-relative">
	<div class="card-body">
		<div class="d-flex justify-content-between mb-4">
			<?php silicon_the_post_date( 'slider' ); ?>
		</div>
		<h3 class="h4">
			<a class="strethed-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<div class="mb-0-last-child mb-4"><?php the_excerpt(); ?></div>
		<div class="d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center fw-bold text-dark text-decoration-none me-3">
				<?php
				echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array( 'class' => 'rounded-circle me-3' ) );
				echo esc_html( get_the_author() );
				?>
			</div>
			<?php if ( get_comments_number() > 0 ) : ?>
				<div class="d-flex align-items-center text-muted">
					<i class="bx bx-comment fs-lg me-1"></i>
					<span class="fs-sm"><?php echo esc_html( get_comments_number() ); ?></span>
				</div>
			<?php else : ?>
				<div class="d-flex align-items-center text-muted">
					<span class="fs-sm"><?php echo esc_html__( 'No comments', 'silicon' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</article>
