<?php

$projectid = get_the_ID();
if ( has_post_thumbnail() ) :
	?><div id="<?php echo esc_attr( 'project-' . $projectid ); ?>" class="swiper-tab position-md-absolute w-100 h-100 bg-position-center bg-repeat-0 bg-size-cover <?php echo esc_attr( $active ); ?>" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url() ); ?>')">
		<div class="ratio ratio-1x1"></div>
	 </div>
	<?php
endif;
