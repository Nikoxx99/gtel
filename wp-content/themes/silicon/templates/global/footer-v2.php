<?php
/**
 * Template to display Footer v2.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$footer_skin    = silicon_footer_skin();
$footer_bg      = silicon_footer_bg();
$footer_classes = 'footer border-top pt-5 pb-4 pb-lg-5';

if ( 'bg-darken' === $footer_skin ) {
	$footer_classes .= ' ' . $footer_bg;
}

?>
<footer id="colophon" class="<?php echo esc_attr( $footer_classes ); ?>">
	<div class="container text-center pt-2 pt-md-4 pt-lg-5 pb-xl-3">
		<?php do_action( 'silicon_footer' ); ?>
	</div>
</footer>


