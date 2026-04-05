<?php
/**
 * Portfolio list view structure
 */

$col_class = 'order-md-1 pt-md-4 pt-lg-5';
if ( has_post_thumbnail() ) {
	$col_class .= ' col-md-6';
} else {
	$col_class .= ' col-12';
}

?><div class="row pb-5 mb-md-4 mb-lg-5">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="col-md-6 pb-1 mb-3 pb-md-0 mb-md-0 order-md-2" data-jarallax-element="-25" data-disable-parallax-down="md">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="float-md-end">
				<?php
				the_post_thumbnail(
					'full',
					[
						'class' => 'rounded-3',
						'style' => 'width:600px',
					]
				);
				?>
			</a>
		</div>
		<?php
	endif;
	?>
	<div class="<?php echo esc_attr( $col_class ); ?>" data-jarallax-element="25" data-disable-parallax-down="md">
		<div class="pe-md-4 me-md-2">
			<div class="fs-sm text-muted mb-1"><?php echo get_the_date(); ?></div>
			<h2 class="project-title h3"><?php the_title(); ?></h2>
			<?php render_category(); ?>
			<p class="project-description d-md-none d-lg-block pb-3 mb-2 mb-md-3"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="project-link btn btn-outline-primary">
				<?php echo esc_html( 'View more' ); ?>
			</a>
		</div>
	</div>
</div>
