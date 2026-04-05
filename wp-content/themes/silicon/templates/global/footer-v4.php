<?php
/**
 * Template to display Footer v4.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer_skin    = silicon_footer_skin();
$footer_bg      = silicon_footer_bg();
$footer_classes = 'footer pt-5 pb-4 pb-lg-5';

if ( 'bg-darken' === $footer_skin ) {
	$footer_classes .= ' ' . $footer_bg;
}

?>
<footer id="colophon" class="<?php echo esc_attr( $footer_classes ); ?>">
	<div class="container pt-lg-4">
		<?php do_action( 'silicon_footer' ); ?>
	</div>
</footer>
