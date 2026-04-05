<?php
/**
 * Footer used in 404 v1
 *
 * @package silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$credit_link = '<a href="' . esc_url( 'https://madrasthemes.com/' ) . '" class="nav-link d-inline-block p-0" target="_blank" rel="noopener">MadrasThemes</a>';
/* translators: %s - Credit Link */
$copyright_text = apply_filters( 'silicon_copyright_text', sprintf( esc_html__( '&copy; All Rights reserved. Made by %s', 'silicon' ), $credit_link ) );

?>
<footer class="container text-md-start text-center py-lg-5 py-4" style="transform: translateY(-100%);">
	<div class="row">
		<div class="col-xl-11 offset-xl-1">
			<p class="fs-sm text-center text-md-start mb-0"><?php echo wp_kses_post( $copyright_text ); ?></p>
		</div>
	</div>
</footer>