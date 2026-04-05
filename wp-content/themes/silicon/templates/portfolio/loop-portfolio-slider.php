<?php
$projectid  = get_the_ID();
$categories = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type' ); ?>
<div class="swiper-slide" data-swiper-tab="#<?php echo esc_attr( 'project-' . $projectid ); ?>">
	<h2 class="project-title h1 mb-4"><?php the_title(); ?></h2>
	<p class="project-description pb-3 mb-3"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
	<table class="mb-5">
		<tbody >
			<?php do_action( 'silicon_portfolio_slider_meta_before' ); ?>
			<?php if ( ! empty( $categories ) ) : ?>
			<tr>
				<th class="text-dark fw-bold pb-2" scope="row"><?php echo esc_html__( 'Category', 'silicon' ); ?></th>
				<td class="ps-3 ps-sm-4 pb-2"><?php echo esc_html( wp_strip_all_tags( get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', '/ ' ) ) ); ?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<th class="text-dark fw-bold pb-2" scope="row"><?php echo esc_html__( 'Date', 'silicon' ); ?></th>
				<td class="ps-3 ps-sm-4 pb-2"><?php echo get_the_date( 'j F, Y' ); ?></td>
			</tr>
			 <?php do_action( 'silicon_portfolio_slider_meta_after' ); ?>
		</tbody>
	</table>
	<a href="<?php echo esc_url( get_permalink() ); ?>" class="project-link btn btn-primary shadow-primary mt-n2"><?php echo esc_html__( 'Read more', 'silicon' ); ?></a>
</div>
