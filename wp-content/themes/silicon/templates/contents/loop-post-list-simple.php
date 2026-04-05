<?php
/**
 * Template for list simple blog posts.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<h3 class="h4 mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<div class="d-flex align-items-center text-muted pt-1">
	<?php silicon_the_post_date( 'list-simple' ); ?>
	<?php silicon_the_post_meta_icons( 'list-simple' ); ?>
</div>
