<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package silicon
 */

?>
		<?php do_action( 'silicon_content_bottom' ); ?>
	</div><!-- #content -->
</div><!-- #page -->

<?php
do_action( 'silicon_before_footer' );

do_action( 'silicon_footer' );

do_action( 'silicon_after_footer' );

wp_footer();

?>
</body>
</html>
