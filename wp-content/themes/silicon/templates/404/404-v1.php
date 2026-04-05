<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package silicon
 */

$page_variant = get_theme_mod( '404_version' );
$button_text  = apply_filters( 'silicon_404_button_text', get_theme_mod( '404_button_text', esc_html__( 'Go to homepage', 'silicon' ) ) );
$btn_url      = apply_filters( 'silicon_404_button_url', get_theme_mod( '404_button_url', '#' ) );
$btn_color    = apply_filters( 'silicon_404_button_color', get_theme_mod( '404_button_color', 'primary' ) );
$btn_size     = apply_filters( 'silicon_404_button_size', get_theme_mod( '404_button_size', 'lg' ) );

?>

	<section class="d-flex align-items-center min-vh-100 py-5 bg-light" style="background: radial-gradient(144.3% 173.7% at 71.41% 94.26%, rgba(99, 102, 241, 0.1) 0%, rgba(218, 70, 239, 0.05) 32.49%, rgba(241, 244, 253, 0.07) 82.52%);">
		<div class="container my-5 text-md-start text-center">
		  <div class="row align-items-center">

			<!-- Animation -->
			<div class="col-xl-6 col-md-7 order-md-2 ms-n5">
				<?php
					$image_option = wp_get_attachment_image(
						get_theme_mod( '404_image_option' ),
						'480px',
						false,
						array(
							'class' => 'w-100 h-100',
							'alt'   => esc_html_x( '404', 'front-end', 'silicon' ),
						)
					);
					if ( '' !== $image_option ) :
						echo wp_kses_post( apply_filters( '404_image_option', $image_option ) );
					else :
						?>
				<lottie-player src="<?php echo esc_url( get_template_directory_uri() . '/assets/json/animation-404-v1.json' ); ?>" background="transparent" speed="1" loop autoplay></lottie-player>
				<?php endif; ?>
			</div>

			<!-- Text -->
			<div class="col-md-5 offset-xl-1 order-md-1">
			  <h1 class="display-1 mb-sm-4 mt-n4 mt-sm-n5"><?php echo esc_html( get_theme_mod( '404_title', _x( 'Error 404', 'front-end', 'silicon' ) ) ); ?></h1>
			  <p class="mb-md-5 mb-4 mx-md-0 mx-auto pb-2 lead">
						<?php echo esc_html( get_theme_mod( '404_description', _x( 'The page you are looking for was moved, removed or might never existed.', 'front-end', 'silicon' ) ) ); ?>
			  </p>
			  <a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn-<?php echo esc_html( $btn_size ); ?> btn-<?php echo esc_attr( $btn_color ); ?> shadow-<?php echo esc_attr( $btn_color ); ?> w-sm-auto w-100">
				<i class="bx bx-home-alt me-2 ms-n1 lead"></i>
				<?php echo esc_html( $button_text ); ?>
			  </a>
			</div>
		  </div>
		</div>
	  </section>

<?php
