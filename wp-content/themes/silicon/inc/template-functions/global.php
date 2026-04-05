<?php
/**
 * Template functions used globally by the theme.
 *
 * @package silicon
 */

if ( ! function_exists( 'silicon_get_sidebar' ) ) {
	/**
	 * Display silicon sidebar
	 *
	 * @uses get_sidebar()
	 * @since 1.0.0
	 */
	function silicon_get_sidebar() {
		get_sidebar();
	}
}

if ( ! function_exists( 'silicon_display_comments' ) ) {
	/**
	 * Silicon display comments
	 *
	 * @since  1.0.0
	 */
	function silicon_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || 0 !== intval( get_comments_number() ) ) :
			comments_template();
		endif;
	}
}

if ( ! function_exists( 'silicon_comment' ) ) {
	/**
	 * Silicon comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args the comment args.
	 * @param int   $depth the comment depth.
	 * @since 1.0.0
	 */
	function silicon_comment( $comment, $args, $depth ) {
		if ( 'div' === $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment-reply-target';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}

		$comment_class      = empty( $args['has_children'] ) ? '' : 'parent';
		$comment_meta_class = 'comment-meta commentmetadata d-flex align-items-center justify-content-between pb-2 mb-1';
		$comment_text_class = 'comment-text mb-0-last-child';

		if ( 1 < $depth ) {
			$comment_meta_class .= ' ps-3';
			$comment_text_class .= ' ps-3';
		} else {
			$comment_class .= ' pt-4 border-bottom mb-2 pb-[2rem]';
		}

		if ( ! empty( $args['has_children'] ) ) {
			$comment_text_class .= ' pb-2';
		}

		?>
		<<?php echo esc_attr( $tag ); ?> <?php comment_class( trim( $comment_class ) ); ?> id="comment-<?php comment_ID(); ?>">
		<div class="comment-body">
		<?php if ( 1 < $depth ) : ?>
		<div class="position-relative ps-4 mt-4">
			<span class="position-absolute top-0 start-0 w-1 h-100 bg-primary"></span>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $comment_meta_class ); ?>">
			<div class="comment-author vcard d-flex align-items-center me-3">
				<?php echo get_avatar( $comment, 48, '', '', array( 'class' => 'rounded-circle me-3' ) ); ?>
				<div class="comment-author-body">
					<h6 class="fw-semibold mb-0"><?php echo wp_kses_post( get_comment_author_link() ); ?></h6>
					<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="comment-date fs-sm text-muted text-decoration-none">
						<?php echo '<time datetime="' . esc_attr( get_comment_date( 'c' ) ) . '">' . esc_html( get_comment_date() ) . '</time>'; ?>
					</a>
					<?php if ( '0' === $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation fs-sm text-muted d-block"><?php esc_html_e( 'Your comment is awaiting moderation.', 'silicon' ); ?></em>
					<?php endif; ?>
				</div>
			</div>
			<div class="reply nav-link fs-sm px-0">
			<?php
			comment_reply_link(
				array_merge(
					$args,
					array(
						'add_below' => $add_below,
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<i class="bx bx-share fs-lg me-2"></i>',
					)
				)
			);
			?>
			</div>
		</div>
		
		<?php if ( 'div' !== $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
		<?php endif; ?>
			
			<div class="<?php echo esc_attr( $comment_text_class ); ?>">
			<?php comment_text(); ?>
			<?php edit_comment_link( esc_html__( 'Edit', 'silicon' ), '  ', '' ); ?>
			</div>
		</div>

		<div id="comment-reply-target-<?php comment_ID(); ?>" class="comment-reply-target"></div>
		
		<?php if ( 'div' !== $args['style'] ) : ?>
		</div>
		<?php endif; ?>
		
		<?php if ( 1 < $depth ) : ?>
		</div><!-- /.position-relative -->
		<?php else : ?>
		
		<?php endif; ?>
		<?php
	}
}

if ( ! function_exists( 'silicon_page_loading_spinner' ) ) {
	/**
	 * Display page loading spinner.
	 */
	function silicon_page_loading_spinner() {
		$loading_text = apply_filters( 'silicon_page_loading_text', esc_html__( 'Loading...', 'silicon' ) );

		if ( silicon_is_page_loader_enabled() ) :
			?>
		<div class="page-loading active">
			<div class="page-loading-inner">
				<div class="page-spinner"></div><span><?php echo esc_html( $loading_text ); ?></span>
			</div>
		</div>
			<?php
		endif;
	}
}

/**
 * Get if page loader feature is enabled or not.
 *
 * @return bool
 */
function silicon_is_page_loader_enabled() {
	return apply_filters( 'silicon_page_loader_enabled', true );
}

/**
 * Get if page loader feature is enabled or not.
 *
 * @return bool
 */
function silicon_is_page_loader() {
	$page_loader = 'yes' === get_theme_mod( 'enable_silicon_page_loader', 'no' ) ? true : false;
	return $page_loader;
}

if ( ! function_exists( 'silicon_offcanvas_blog_toggler' ) ) {
	/**
	 * Display a toggle button in Blog page.
	 */
	function silicon_offcanvas_blog_toggler() {
		?>
		<!-- Offcanvas blog toggler -->
		<button type="button" data-bs-toggle="offcanvas" data-bs-target="#blog-sidebar" aria-controls="blog-sidebar" class="btn btn-sm btn-primary fixed-bottom d-lg-none w-100 rounded-0">
			<i class='bx bx-sidebar fs-xl me-2'></i>
			<?php echo esc_html__( 'Sidebar', 'silicon' ); ?>
		</button>
		<?php
	}
}

if ( ! function_exists( 'silicon_back_to_top' ) ) {
	/**
	 * Display back to top button.
	 */
	function silicon_back_to_top() {
		?>
		<!-- Back to top button -->
		<a href="#top" class="btn-scroll-top mb-4 mb-lg-0" data-scroll>
			<span class="btn-scroll-top-tooltip text-muted fs-sm me-2"><?php echo esc_html__( 'Top', 'silicon' ); ?></span>
			<i class="btn-scroll-top-icon bx bx-chevron-up"></i>
		</a>
		<?php
	}
}

if ( ! function_exists( 'silicon_pagination' ) ) {
	/**
	 * Displays pagination.
	 */
	function silicon_pagination() {
		?>
		<nav aria-label="<?php echo esc_attr__( 'Page Navigation', 'silicon' ); ?>"><?php silicon_bootstrap_pagination( null, true, 'justify-content-center pt-md-4 pt-2' ); ?></nav>
		<?php
	}
}

if ( ! function_exists( 'silicon_breadcrumb' ) ) {

	/**
	 * Output the Silicon Breadcrumb.
	 *
	 * @param array $args Arguments.
	 */
	function silicon_breadcrumb( $args = array() ) {
		$args = wp_parse_args(
			$args,
			apply_filters(
				'silicon_breadcrumb_defaults',
				array(
					'delimiter'   => '',
					'wrap_before' => '<nav aria-label="breadcrumb" class="container pt-4 mt-lg-3"><ol class="breadcrumb mb-0">',
					'wrap_after'  => '</ol></nav>',
					'before'      => '<li class="breadcrumb-item">',
					'after'       => '</li>',
					'home'        => _x( 'Home', 'breadcrumb', 'silicon' ),
				)
			)
		);

		require_once get_template_directory() . '/inc/class-silicon-breadcrumb.php';

		$breadcrumbs = new Silicon_Breadcrumb();

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'silicon_breadcrumb_home_url', home_url() ), '<i class="bx bx-home-alt fs-lg me-1"></i>' );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		do_action( 'silicon_breadcrumb', $breadcrumbs, $args );

		if ( ! empty( $args['breadcrumb'] ) ) {

			$output = wp_kses_post( $args['wrap_before'] );

			foreach ( $args['breadcrumb'] as $key => $crumb ) {

				if ( ! empty( $crumb[1] ) && count( $args['breadcrumb'] ) !== $key + 1 ) {
					$output .= wp_kses_post(
						sprintf(
							'%s<a href="%s" class="text-gray-700">%s</a>%s',
							$args['before'],
							esc_url( $crumb[1] ),
							$crumb[0],
							$args['after']
						)
					);
				} else {
					$output .= '<li class="breadcrumb-item active"><span>' . esc_html( $crumb[0] ) . '</span></li>';
				}
			}

			$output .= wp_kses_post( $args['wrap_after'] );

			echo wp_kses_post( apply_filters( 'silicon_breadcrumb_html', $output ) );
		}
	}
}

if ( ! function_exists( 'silicon_the_post_thumbnail' ) ) {
	/**
	 * Display the post thumbnail with aspect ratio.
	 *
	 * @param string|int[] $size       Optional. Image size. Accepts any registered image size name, or an array of width and height values in pixels (in that order). Default 'post-thumbnail'.
	 * @param string|array $attr       Optional. Query string or array of attributes. Default empty.
	 * @param string       $width      Optional. Width of the image aspect ratio.
	 * @param string       $height     Optional. Height of the image aspect ratio.
	 * @param string       $wrap_class Optional. Wrap class for the post thumbnail.
	 */
	function silicon_the_post_thumbnail( $size = 'post-thumbnail', $attr = '', $width = '', $height = '', $wrap_class = '' ) {
		if ( empty( $height ) && empty( $width ) ) {
			the_post_thumbnail( $size, $attr );
		} else {
			$wrap_class .= ' aspect-ratio aspect-w-' . $width . ' aspect-h-' . $height;
			$img_class   = 'w-full h-full object-center object-cover mw-none';

			if ( isset( $attr['class'] ) ) {
				$attr['class'] .= ' ' . $img_class;
			} else {
				if ( is_array( $attr ) ) {
					$attr['class'] = $img_class;
				} else {
					$attr = array( 'class' => $img_class );
				}
			}
			?>
			<div class="<?php echo esc_attr( trim( $wrap_class ) ); ?>">
				<?php the_post_thumbnail( $size, $attr ); ?>
			</div>
			<?php
		}
	}
}
