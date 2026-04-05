<?php
/**
 * Template used to display post content.
 *
 * @package silicon
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to silicon_loop_post action.
	 *
	 * @hooked silicon_post_header          - 10
	 * @hooked silicon_post_content         - 30
	 * @hooked silicon_post_taxonomy        - 40
	 */
	do_action( 'silicon_loop_post' );
	?>

</article><!-- #post-## -->
