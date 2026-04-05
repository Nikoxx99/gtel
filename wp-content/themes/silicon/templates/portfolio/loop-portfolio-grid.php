<?php
/**
 * Portfolio grid view structure
 */

?>
<div class="card card-portfolio">
	<div class="card-img">
		<?php the_post_thumbnail( 'full' ); ?>
	</div>
	<div class="card-body">
		<h2 class="h4 mb-2">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="stretched-link"><?php the_title(); ?></a>
		</h2>
		<div class="card-portfolio-meta">
			<span class="text-muted"><?php echo esc_html( wp_strip_all_tags( get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', ' / ' ) ) ); ?></span>
		</div>
	</div>
</div>
<?php
