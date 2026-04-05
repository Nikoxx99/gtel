<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package silicon
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">
		<?php
		/**
		 * Functions hooked in to silicon_page add_action
		 *
		 * @hooked silicon_page_header          - 10
		 * @hooked silicon_page_content         - 20
		 */
		do_action( 'silicon_page' );
		?>
	</div>
</div><!-- #post-## -->
