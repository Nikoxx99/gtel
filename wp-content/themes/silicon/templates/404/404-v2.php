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

<section class="container d-flex flex-column h-100 align-items-center position-relative zindex-5 pt-5">
	<div class="text-center pt-5 pb-3 mt-auto">

		<!-- Parallax gfx (Light version) -->
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
		<div class="parallax mx-auto d-dark-mode-none" style="max-width: 574px;">
			<div class="parallax-layer" data-depth="-0.15">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404/light/layer01.png' ); ?>" alt="<?php echo esc_attr__( 'Layer', 'silicon' ); ?>">
			</div>
			<div class="parallax-layer" data-depth="0.12">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404/light/layer02.png' ); ?>" alt="<?php echo esc_attr__( 'Layer', 'silicon' ); ?>">
			</div>
			<div class="parallax-layer zindex-5" data-depth="-0.12">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404/light/layer03.png' ); ?>" alt="<?php echo esc_attr__( 'Layer', 'silicon' ); ?>">
			</div>
		</div>

		<!-- Parallax gfx (Dark version) -->
		<div class="parallax mx-auto d-none d-dark-mode-block" style="max-width: 574px;">
			<div class="parallax-layer" data-depth="-0.15">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404/dark/layer01.png' ); ?>" alt="<?php echo esc_attr__( 'Layer', 'silicon' ); ?>">
			</div>
			<div class="parallax-layer" data-depth="0.12">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404/dark/layer02.png' ); ?>" alt="<?php echo esc_attr__( 'Layer', 'silicon' ); ?>">
			</div>
			<div class="parallax-layer zindex-5" data-depth="-0.12">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/404/dark/layer03.png' ); ?>" alt="<?php echo esc_attr__( 'Layer', 'silicon' ); ?>">
			</div>
		</div>
		<?php endif; ?>

		<h1 class="visually-hidden"><?php echo esc_html( '404' ); ?></h1>
		<h2 class="display-5"><?php echo esc_html( get_theme_mod( '404_title', _x( 'Error 404', 'front-end', 'silicon' ) ) ); ?></h2>
		<p class="fs-xl pb-3 pb-md-0 mb-md-5"><?php echo esc_html( get_theme_mod( '404_description', _x( 'The page you are looking for was moved, removed or might never existed.', 'front-end', 'silicon' ) ) ); ?></p>
		<a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn-<?php echo esc_html( $btn_size ); ?> btn-<?php echo esc_attr( $btn_color ); ?> shadow-<?php echo esc_attr( $btn_color ); ?> w-sm-auto w-100">
		<i class="bx bx-home-alt me-2 ms-n1 lead"></i>
		<?php echo esc_html( $button_text ); ?>
		</a>
	</div>
</section>
