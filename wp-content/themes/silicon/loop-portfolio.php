<?php
/**
 * The loop template file for post type jetpack-portfolio.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package silicon
 */

do_action( 'silicon_loop_portfolio_before' );

$view = silicon_portfolio_layout();

$row    = 1;
$column = 1;


if ( 'slider' !== $view ) {
	$section_class = 'grid' === $view ? 'container pb-5 mb-2 mb-md-4 mb-lg-5' : 'container pb-2 pb-lg-3';

	?><section class="<?php echo esc_attr( $section_class ); ?>">
		<?php
		if ( 'grid' === $view ) {
			?>
			<div class="row pb-lg-3">
			<?php
		}
}

if ( 'slider' === $view ) {
	?>
	<div class="d-none d-lg-block" style="height: 160px;"></div>
		<div class="d-none d-md-block d-lg-none" style="height: 80px;"></div>
		<div class="swiper pt-4 pt-md-0" data-swiper-options='{
			"spaceBetween": 30,
			"loop": true,
			"tabs": true,
			"pagination": {
			  "el": ".swiper-pagination",
			  "type": "fraction"
			},
			"navigation": {
			  "prevEl": ".btn-prev",
			  "nextEl": ".btn-next"
			}
		  }'>
		<div class="swiper-wrapper">
		<?php
}
while ( have_posts() ) :

	the_post();

	if ( 'list' === $view ) :

		$count = $row % 2;
		if ( 0 === $count ) {
			silicon_get_template( 'templates/portfolio/loop-portfolio-list-even.php' );
		} else {
			silicon_get_template( 'templates/portfolio/loop-portfolio-list-odd.php' );
		}

		$row++;

		elseif ( 'grid' === $view ) :

			$column = 2 - ( $column % 2 );

			$total = $row + $column;

			if ( 0 === $total % 2 ) {
				$width = 'col-md-5';
			} else {
				$width = 'col-md-7';
			}

			echo '<div class="' . esc_attr( $width ) . ' mb-2">';

			silicon_get_template( 'templates/portfolio/loop-portfolio-grid.php' );

			echo '</div>';

			if ( 0 === $column % 2 ) {
				$row++;
			}

			$column++;

		elseif ( 'slider' === $view ) :
			silicon_get_template( 'templates/portfolio/loop-portfolio-slider.php' );
		endif;

	endwhile;
if ( 'slider' !== $view ) {
	if ( 'grid' === $view ) {
		?>
		</div>
		<?php silicon_pagination(); ?>
		<?php
	}
	?>
	</section>
	<?php
}
do_action( 'silicon_loop_portfolio_after' );
