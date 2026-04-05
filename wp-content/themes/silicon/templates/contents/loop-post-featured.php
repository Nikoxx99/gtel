<?php
/**
 * Featured Post Template.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<h1 class="display-5 pb-md-3"><?php the_title(); ?></h1>
<div class="d-flex flex-wrap mb-md-5 mb-4 pb-md-2 text-white">
	<?php silicon_the_post_categories( 'featured' ); ?>
	<?php silicon_the_post_date( 'featured', true ); ?>
	<?php silicon_the_post_meta_icons( 'featured' ); ?>
</div>
<a href="<?php the_permalink(); ?>" class="btn btn-lg btn-primary">
	<?php echo esc_html__( 'Read article', 'silicon' ); ?>
	<i class="bx bx-right-arrow-alt ms-2 me-n1 lead"></i>
</a>
