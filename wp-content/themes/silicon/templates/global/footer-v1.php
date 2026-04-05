<?php
/**
 * Template to display Footer v1.
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$heart_icon  = '<i class="bx bx-heart d-inline-block fs-lg text-gradient-primary align-middle mt-n1 mx-1"></i>';
$credit_link = '<a href="' . esc_url( 'https://madrasthemes.com/' ) . '" class="text-muted" target="_blank" rel="noopener">MadrasThemes</a>';
/* translators: %1$s - Heart Icon, %2$s - Credit Link */
$copyright_text = apply_filters( 'silicon_copyright_text', sprintf( esc_html__( '&copy; All Rights reserved. Made with %1$s by %2$s', 'silicon' ), $heart_icon, $credit_link ) );
?>
<footer id="colophon" class="site-footer text-center footer border-top pt-5 pb-4 pb-lg-5">
	<p class="text-muted mb-0">
		<?php echo wp_kses_post( $copyright_text ); ?>
	</p>
</footer>
