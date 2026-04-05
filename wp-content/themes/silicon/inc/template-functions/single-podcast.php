<?php
/**
 * Template functions related to single podcast.
 *
 * @package silicon
 */

if ( ! function_exists( 'silicon_single_podcast_header' ) ) {
	/**
	 * Display Single Post Header
	 */
	function silicon_single_podcast_header() {
		?>
		<section class="container mt-4 mb-5 pt-2 pb-lg-5">
			<div class="row gy-4">
				<?php if ( has_post_thumbnail() ) : ?>
				<div class="col-lg-7 col-md-6">
					<?php silicon_the_post_thumbnail( 'full', [ 'class' => 'rounded-3' ], '', '' ); ?>
				</div>
				<?php endif; ?>
				<div class="col-lg-5 col-md-6">
					<div class="ms-xl-5 ms-lg-4 ps-xxl-34">
						<h3 class="h6 mb-2">
						<svg class="me-2 mt-n1" xmlns="http://www.w3.org/2000/svg" width="24" height="25" fill="none"><path d="M20 12.5003v-1.707c0-4.44199-3.479-8.16099-7.755-8.28999-2.204-.051-4.251.736-5.816 2.256S4 8.31831 4 10.5003v2c-1.103 0-2 .897-2 2v4c0 1.103.897 2 2 2h2v-10c0-1.63699.646-3.16599 1.821-4.30599s2.735-1.739 4.363-1.691c3.208.096 5.816 2.918 5.816 6.28999v9.707h2c1.103 0 2-.897 2-2v-4c0-1.103-.897-2-2-2z" fill="url(#A)"></path><path d="M7 12.5003h2v8H7v-8zm8 0h2v8h-2v-8z" fill="url(#A)"></path><defs><linearGradient id="A" x1="2" y1="11.5437" x2="22" y2="11.5437" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#6366f1"></stop><stop offset=".5" stop-color="#8b5cf6"></stop><stop offset="1" stop-color="#d946ef"></stop></linearGradient></defs></svg>
						<?php echo esc_html( apply_filters( 'silicon_podcast_pretitle', 'Podcast' ) ); ?>
						</h3>
						<h1 class="display-5 pb-md-3"><?php the_title(); ?></h1>
						<div class="d-flex align-items-center flex-wrap text-muted mb-lg-5 mb-md-4 mb-3">
							<div class="fs-xs border-end pe-3 me-3 mb-2"><?php silicon_single_podcast_cat(); ?></div>
							<?php silicon_the_post_date( 'single', true ); ?>
							<div class="d-flex mb-2">
								<div class="d-flex align-items-center me-3">
									<i class="bx bx-comment fs-base me-1"></i>
									<span class="fs-sm"><?php echo esc_html( get_comments_number() ); ?></span>
								</div>
							</div>
						</div>
						<div class="mb-0-last-child fs-lg"><?php the_content(); ?></div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_podcast_timeline_content' ) ) {
	/**
	 * Display Single Post Header
	 */
	function silicon_single_podcast_timeline_content() {
		?>
		<section class="container mb-5 pb-lg-5">
			<div class="row gy-md-5 gy-4">
			<!-- Author -->
				<div class="col-lg-3 col-md-4 order-md-2 position-relative">
					<div class="sticky-top ms-xxl-5 ps-lg-4" style="top: 105px !important;">
						<div class="d-flex align-items-center position-relative mb-lg-5 mb-4">
							<?php
							silicon_single_podcast_avatar();
							?>
						</div>
					</div>
				</div>

				<!-- Player + Timeline -->
				<?php if ( ! empty( silicon_get_field( 'upload_audio' ) ) ) : ?>
				<div class="col-lg-9 col-md-8 order-md-1">
					<div class="card p-lg-4 p-md-2 mb-4 mb-lg-5">

					<!-- Audio player -->
						<?php silicon_single_podcast_audio_player(); ?>
					</div>
					<?php
					silicon_single_podcast_acf_timeline();
					?>
				</div>
			<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_podcast_audio_player' ) ) {
	/**
	 * Display Single Post Header
	 */
	function silicon_single_podcast_audio_player() {
		?>
		<div class="audio-player card-body p-2 p-sm-4" style="--buffered-width:76.1905%;">
			<audio src="<?php echo esc_url( silicon_get_field( 'upload_audio' ) ); ?>" preload="auto"></audio>
			<button type="button" class="ap-play-button btn btn-icon btn-primary shadow-primary"></button>
			<span class="ap-current-time flex-shr fw-medium mx-3 mx-lg-4">0:00</span>
			<input type="range" class="ap-seek-slider" max="21" value="0">
			<span class="ap-duration flex-shr fw-medium mx-3 mx-lg-4">0:00</span>
			<div class="dropup">
			<button type="button" class="ap-volume-button btn btn-icon btn-secondary" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
				<i class="bx bx-volume-full"></i>
			</button>
			<div class="dropdown-menu dropdown-menu-dark my-1">
				<input type="range" class="ap-volume-slider" max="100" value="100">
			</div>
			</div>
			<a href="<?php echo esc_url( silicon_get_field( 'upload_audio' ) ); ?>" download="audio-sample" class="btn btn-icon btn-secondary ms-2">
			<i class="bx bx-download"></i>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_podcast_avatar' ) ) {
	/**
	 * Display Single Post Header
	 */
	function silicon_single_podcast_avatar() {

		$author_id  = get_the_author_meta( 'ID' );
		$author_url = get_author_posts_url( $author_id );
		echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 60, '', '', [ 'class' => 'rounded-circle me-3' ] ) );
		?>
		<div>
		<h4 class="h6 mb-1"><?php echo esc_html( apply_filters( 'silicon_podcast_avatar_text', 'Hosted by' ) ); ?></h4>
		<a href="<?php echo esc_url( $author_url ); ?>" class="fw-semibold stretched-link"><?php the_author(); ?></a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_podcast_acf_timeline' ) ) {
	/**
	 * Display Single Post Header
	 */
	function silicon_single_podcast_acf_timeline() {
		$timelines = silicon_get_field( 'timeline' );
		if ( $timelines ) {
			?>
			<h3 class="h4 mb-4 pt-2 pt-md-0"><?php echo esc_html( apply_filters( 'silicon_podcast_timeline_heading', 'Timeline' ) ); ?></h3>
			<?php
			$timelines = explode( "\n", $timelines );
			if ( isset( $timelines ) ) {
				?>
				<ul class="list-unstyled mb-0">
					<?php
					foreach ( $timelines as $timeline ) {

						if ( empty( trim( $timeline ) ) ) {
							continue;
						}
						$detail = explode( '|', $timeline );
						$time   = isset( $detail[0] ) ? $detail[0] : '';
						$text   = isset( $detail[1] ) ? $detail[1] : '';
						if ( $time || $text ) {
							?>
						<li class="d-flex mb-2">
							<span class="flex-shrink-0 fw-medium text-nowrap me-2" style="width: 80px;"><?php echo esc_html( $time ); ?></span>
							<?php echo esc_html( $text ); ?>
						</li>
							<?php
						}
					}
					?>
				</ul>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'silicon_single_podcast_cat' ) ) {
	/**
	 * Display Single Post Category
	 */
	function silicon_single_podcast_cat() {

		$terms = get_the_terms( get_the_ID(), 'category' );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $key => $term ) {
				?>
				<span class = "badge bg-faded-primary text-primary fs-base mb-1">
				<?php
				$string = str_replace( ' ', ' ', $term->name );
				echo esc_html( $string );
				?>
				</span>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'silicon_single_podcast_comments' ) ) {
	/**
	 * Display Single Post Comments.
	 */
	function silicon_single_podcast_comments() {
		?>
		<section class="container mb-4 pb-lg-3">
			<div class="row">

			<!-- Comments -->
				<div class="col-lg-9">
				<?php silicon_display_comments(); ?>
				</div>
			</div>
		</section>
		<?php
	}
}

if ( ! function_exists( 'silicon_single_podcast_related_posts' ) ) {
	/**
	 * Silicon Related posts static content.
	 */
	function silicon_single_podcast_related_posts() {

		$posts_content = get_theme_mod( 'single_podcast_layout', '' );

		if ( silicon_is_mas_static_content_activated() && ! empty( $posts_content ) ) {
			print( silicon_render_content( $posts_content, false ) ); //phpcs:ignore
		}

	}
}
