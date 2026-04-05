<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package silicon
 */

if ( ! silicon_has_sidebar() ) {
	return;
}

silicon_offcanvas_blog_toggler();
?>

<div id="secondary" class="widget-area site-sidebar sidebar-blog col-xl-3 col-lg-4" role="complementary">
	<div class="offcanvas offcanvas-end offcanvas-expand-lg" id="blog-sidebar" tabindex="-1">
		<!-- Header -->
		<div class="offcanvas-header border-bottom">
			<h3 class="offcanvas-title fs-lg"><?php echo esc_html__( 'Sidebar', 'silicon' ); ?></h3>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
		</div>
		<!-- Body -->
		<div class="offcanvas-body">
			<?php dynamic_sidebar( 'sidebar-blog' ); ?>
		</div>
	</div>
</div><!-- #secondary -->
