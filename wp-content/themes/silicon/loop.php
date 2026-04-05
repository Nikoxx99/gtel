<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package silicon
 */

do_action( 'silicon_loop_before' );

$grid_class     = 'masonry-grid row row-cols-1 g-4';
$sidebar_layout = silicon_get_blog_sidebar_layout();
$view_style     = silicon_get_blog_view_style_layout();
$row            = 1;

if ( 'sidebar-left' === $sidebar_layout ) {
	$wrap_class = 'ps-xl-5';
} else {
	$wrap_class = 'pe-xl-5';
}
if ( 'grid-v2' === $view_style ) {
	$wrap_class = '';
	$grid_class = 'row row-cols-1 gy-md-4 gy-2';

}

if ( silicon_has_sidebar() ) {
	$grid_class .= ' row-cols-sm-2';
} else {
	$grid_class .= ' row-cols-sm-3';
}

if ( 'default' === $view_style || 'grid-v2' === $view_style ) {
	?>
	<div class="<?php echo esc_attr( $wrap_class ); ?>">
		<div class="<?php echo esc_attr( $grid_class ); ?>">
		<?php
}

while ( have_posts() ) :
	the_post();
	if ( 'default' === $view_style ) {
		get_template_part( 'templates/contents/loop-post', 'default' );
	} elseif ( 'grid-v2' === $view_style ) {
		?><div class="col pb-3 si-article"><?php
        get_template_part( 'templates/contents/loop-post', 'grid-v2' );
        ?></div><?php
	} else {
		$count = $row % 2;
		if ( 0 === $count ) {
			get_template_part( 'templates/contents/loop-post', 'list-v1-even' );
		} else {
			get_template_part( 'templates/contents/loop-post', 'list-v1-odd' );
		}
		$row++;
	}

		endwhile;
if ( 'default' === $view_style || 'grid-v2' === $view_style ) {
	?>
	</div>
	<?php
}

	/**
	 * Functions hooked in to silicon_paging_nav action
	 *
	 * @hooked silicon_paging_nav - 10
	 */
	do_action( 'silicon_loop_after' );
if ( 'default' === $view_style || 'grid-v2' === $view_style ) {
	?>
	</div>
	<?php
}
