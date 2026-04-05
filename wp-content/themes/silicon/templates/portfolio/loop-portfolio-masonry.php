<a href="<?php echo esc_url( get_permalink() ); ?>" class="card card-portfolio card-hover bg-transparent border-0">
	<div class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
		<span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50 rounded-3"></span>
		<div class="position-relative zindex-2">
			<h3 class="project-title h5 text-light mb-2"><?php the_title(); ?></h3>
			<span class="project-type fs-sm text-light opacity-70"><?php echo esc_html( wp_strip_all_tags( get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', '/ ' ) ) ); ?></span>
		</div>
	</div>
	<div class="card-img">
		<?php
			the_post_thumbnail(
				'full',
				[
					'class' => 'rounded-3',
				]
			);
			?>
	</div>
</a>
