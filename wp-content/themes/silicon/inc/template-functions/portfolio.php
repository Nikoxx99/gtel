<?php
/**
 * Template functions related to portfolio
 */

if ( ! function_exists( 'silicon_portfolio_layout' ) ) {
	/**
	 * Portfolio Layout
	 */
	function silicon_portfolio_layout() {
		$layout = get_theme_mod( 'portfolio_layout', 'list' );
		return sanitize_key( apply_filters( 'portfolio_layout', $layout ) );
	}
}

if ( ! function_exists( 'silicon_portfolio_list_filters' ) ) :
	/**
	 * Output the portfolio category filter.
	 */
	function silicon_portfolio_list_filters() {

		?>
		<form action="<?php echo esc_url( home_url() ); ?>" method="get">
				<?php
				wp_dropdown_categories(
					array(
						'class'           => 'form-select',
						'taxonomy'        => 'jetpack-portfolio-type',
						'id'              => 'portfolio-dropdown',
						'name'            => 'jetpack-portfolio-type',
						'show_option_all' => esc_html__(
							'All Categories',
							'silicon'
						),
					)
				);
				?>
			</form>
			<script>
			/* <![CDATA[ */
			(function() {
				var dropdown = document.getElementById( "portfolio-dropdown" );
				function onCatChange() {
					const selectedCat = dropdown.options[ dropdown.selectedIndex ].value;
					if ( selectedCat >= 0 || selectedCat ) {
						dropdown.parentNode.submit();
					}
				}
				dropdown.onchange = onCatChange;
			})();
			/* ]]> */
			</script>
		<?php
	}
endif;


if ( ! function_exists( 'silicon_portfolio_links' ) ) :
	/**
	 * Output the portfolio category filter.
	 */
	function silicon_portfolio_links() {

		if ( has_nav_menu( 'portfolio' ) ) {
			wp_nav_menu(
				apply_filters(
					'silicon_portfolio_args',
					[
						'theme_location' => 'portfolio',
						'walker'         => new WP_Bootstrap_Navwalker(),
						'container'      => false,
						'menu_id_prefix' => 'portfolio',
						'menu_class'     => 'nav nav-tabs-alt flex-nowrap border-0',
						'item_class'     => [ 'nav-item' ],
						'anchor_class'   => [ 'nav-link', 'text-nowrap' ],

					]
				)
			);
		}
	}
endif;

if ( ! function_exists( 'silicon_portfolio_list_header' ) ) :
	/**
	 * Portfolio list header
	 */
	function silicon_portfolio_list_header() {
		$title = apply_filters( 'silicon_portfolio_list_title', esc_html__( 'Portfolio List', 'silicon' ) );
		?>
		<section class="container d-sm-flex align-items-center justify-content-between pb-4 mb-2 mb-lg-3">
			<h1 class="mb-sm-0 me-sm-3"><?php echo esc_html( $title ); ?></h1>
			<?php silicon_portfolio_list_filters(); ?>
		</section>
		<?php
	}
endif;

if ( ! function_exists( 'silicon_portfolio_grid_header' ) ) :
	/**
	 * Portfolio list header
	 */
	function silicon_portfolio_grid_header() {
		$title = apply_filters( 'silicon_portfolio_grid_title', esc_html__( 'Portfolio Grid', 'silicon' ) );
		?>

		<section class="container d-md-flex align-items-center justify-content-between pb-3">
			<h1 class="text-nowrap mb-md-4 pe-md-5"><?php echo esc_html( $title ); ?></h1>
			<nav class="overflow-auto">
				<?php silicon_portfolio_links(); ?>
			</nav>
		</section>
		<?php
	}
endif;

if ( ! function_exists( 'silicon_portfolio_slider_header' ) ) :
	/**
	 * Portfolio list header
	 */
	function silicon_portfolio_slider_header() {
		$count = 1;
		?>
		<section class="position-relative pt-5 py-lg-5 mt-3 mt-md-4">
			<div class="swiper-tabs position-md-absolute top-0 end-0 w-md-50 h-100">
				<?php
				while ( have_posts() ) :
					the_post();
					$id     = get_the_ID();
					$active = 1 === $count ? 'active' : '';
					if ( has_post_thumbnail() ) :
						silicon_get_template( 'templates/portfolio/loop-portfolio-slider-tabs.php', [ 'active' => $active ] );
					endif;
					$count++;
				endwhile;
				?>
			</div>
			<div class="container pt-1 pt-lg-2">
				<div class="row">
					<div class="col-lg-5 col-md-6">
					<?php
	}
endif;

if ( ! function_exists( 'silicon_portfolio_slider_footer' ) ) :
	/**
	 * Portfolio list headers
	 */
	function silicon_portfolio_slider_footer() {
		$view = silicon_portfolio_layout();
		if ( 'slider' !== $view ) {
			return;
		}
		?>
						</div>
						<div class="d-none d-lg-block" style="height: 160px;"></div>
						<div class="d-none d-md-block d-lg-none" style="height: 80px;"></div>
						<div class="d-md-none" style="height: 40px;"></div>

						<!-- Prev / Next buttons + Counter -->
						<div class="d-flex align-items-center ps-2 pb-5">
							<button type="button" class="btn btn-prev btn-icon btn-sm position-static"><i class="bx bx-chevron-left"></i></button>
							<div class="swiper-pagination position-static w-auto mx-3" style="min-width: 30px;"></div>
							<button type="button" class="btn btn-next btn-icon btn-sm position-static"><i class="bx bx-chevron-right"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
		<?php
	}
endif;

if ( ! function_exists( 'render_category' ) ) :
	/**
	 * Portfolio Category
	 */
	function render_category() {
		?>
		<div class="project-type">

		<?php
			$terms = get_the_terms( get_the_ID(), 'jetpack-portfolio-type' );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $key => $term ) {
				?>
					<a href = "<?php echo esc_url( get_term_link( $term->term_id ) ); ?>" class = "badge bg-faded-primary text-primary fs-sm mb-3 me-2">
					<?php
					$string = str_replace( ' ', ' ', $term->name );
					echo esc_html( $string );
					?>
					</a>
					<?php
			}
		}
		?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'silicon_portfolio_section_header' ) ) :
	/**
	 * Portfolio list header
	 */
	function silicon_portfolio_section_header() {
		$view = silicon_portfolio_layout();
		if ( 'grid' === $view ) {
			silicon_portfolio_grid_header();
		} elseif ( 'list' === $view ) {
			silicon_portfolio_list_header();
		} elseif ( 'slider' === $view ) {
			silicon_portfolio_slider_header();
		}
	}
endif;



if ( ! function_exists( 'silicon_portfolio_slider_list' ) ) {
	/**
	 *  Display single post author_social_profile..
	 */
	function silicon_portfolio_slider_list() {

		$portfolio_slider_list = silicon_get_field( 'portfolio_slider_list' );

		$lists = explode( "\n", $portfolio_slider_list );
		?>
		<?php
		foreach ( $lists as $list ) {
			if ( empty( trim( $list ) ) ) {
				continue;
			}
			$detail = explode( '|', $list );
			if ( ! empty( $detail[0] ) ) :
				$list_heading = $detail[0];
				if ( isset( $detail[1] ) ) {
					$list_data = $detail[1];
				}
				?>
				<tr>
					<th class="text-dark fw-bold pb-2" scope="row"><?php echo esc_html( $list_heading ); ?></th>
					<td class="ps-3 ps-sm-4 pb-2"><?php echo esc_html( $list_data ); ?></td>
				</tr>
				<?php
			endif;
		}
	}
}

if ( ! function_exists( 'silicon_single_project_header' ) ) {
	/**
	 *  Display single project header.
	 */
	function silicon_single_project_header() {
		?>
		<section class="container pb-4 mb-2 mb-lg-3">
			<h1><?php the_title(); ?></h1>
			<p class="text-muted mb-0"><?php echo esc_html( wp_strip_all_tags( get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', ' / ' ) ) ); ?></p>
		</section>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_project_thumbnail' ) ) {
	/**
	 *  Display single project header.
	 */
	function silicon_single_project_thumbnail() {

		?>
	<div class="jarallax" data-jarallax data-speed="0.5">
		<div class="jarallax-img"
		<?php
		if ( has_post_thumbnail() ) :
			?>
			style="background-image: url( <?php the_post_thumbnail_url( get_the_ID(), 'full' ); ?> );"<?php endif; ?>></div>
		<div class="d-none d-xxl-block" style="height: 800px;"></div>
		<div class="d-none d-lg-block d-xxl-none" style="height: 600px;"></div>
		<div class="d-none d-md-block d-lg-none" style="height: 450px;"></div>
		<div class="d-md-none" style="height: 400px;"></div>
		</div>
		<?php

	}
}


