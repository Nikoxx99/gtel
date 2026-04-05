<?php
/**
 * Template used to display post content on single pages.
 *
 * @package silicon
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'silicon_single_podcast_top' );

	/**
	 * Functions hooked into silicon_single_post add_action.
	 *
	 * @hooked silicon_post_header          - 10
	 * @hooked silicon_post_content         - 30
	 */
	do_action( 'silicon_single_podcast' );

	/**
	 * Functions hooked in to silicon_single_post_bottom action
	 *
	 * @hooked silicon_single_podcast_comments         - 10
	 * @hooked silicon_single_podcast_related_posts - 20
	 */
	do_action( 'silicon_single_podcast_after' );
	?>

</article><!-- #post-## -->
