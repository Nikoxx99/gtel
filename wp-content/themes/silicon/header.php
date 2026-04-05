<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package silicon
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="//gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'silicon_before_site' ); ?>

<div id="page" class="hfeed site page-wrapper">
	<?php do_action( 'silicon_before_header' ); ?>

	<header id="masthead" <?php silicon_masthead_class(); ?>>
		<div class="container px-3">
			<?php
			/**
			 * Functions hooked into silicon_header action
			 *
			 * @hooked silicon_skip_links			   - 5
			 * @hooked silicon_site_branding		   - 20
			 * @hooked silicon_header_navbar_offcanvas - 30
			 * @hooked silicon_mode_switcher		   - 40
			 * @hooked silicon_navbar_toggler		   - 50
			 * @hooked silicon_custom_button		   - 60
			 */
			do_action( 'silicon_header' );
			?>
		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to silicon_before_content
	 */
	do_action( 'silicon_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<?php
		do_action( 'silicon_content_top' );
