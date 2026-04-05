<?php
/**
 * Template functions used in loop post.
 *
 * @package silicon
 */

if ( ! function_exists( 'silicon_the_post_meta' ) ) {
	/**
	 * Display the post meta
	 *
	 * @param string $meta The meta value that needs to be displayed.
	 * @param string $view The view for which the meta value needs to be displayed.
	 */
	function silicon_the_post_meta( $meta, $view ) {
		if ( 'post' !== get_post_type() ) {
			return;
		}

		switch ( $meta ) {
			case 'post-author':
				silicon_the_post_author( $view );
				break;
			case 'post-date':
				silicon_the_post_date( $view );
				break;
			case 'post-meta-icons':
				silicon_the_post_meta_icons( $view );
				break;
		}
	}
}

if ( ! function_exists( 'silicon_the_post_meta_icons' ) ) {
	/**
	 * Displays the post meta icons.
	 *
	 * @param string $view The view for which the meta icons are displayed.
	 */
	function silicon_the_post_meta_icons( $view = 'list-view' ) {
		$icon_items = apply_filters(
			'silicon_post_meta_icon_items',
			array()
		);

		if ( ! post_password_required() && ( comments_open() || 0 !== intval( get_comments_number() ) ) ) {
			$icon_items[] = array(
				'icon'  => 'bx bx-comment',
				'value' => get_comments_number(),
				'url'   => get_comments_link(),
			);
		}

		$before     = '';
		$after      = '';
		$icon_size  = '';
		$wrap_class = 'd-flex align-items-center me-3';

		if ( 'list-view' === $view ) {
			$before    = '<div class="d-flex align-items-center text-muted me-n3">';
			$after     = '</div>';
			$icon_size = 'fs-lg';
		} elseif ( 'single' === $view ) {
			$before    = '<div class="d-flex mb-2">';
			$after     = '</div>';
			$icon_size = 'fs-base';
		} elseif ( 'grid-v3' === $view ) {
			$before    = '<div class="d-flex align-items-center me-3">';
			$after     = '</div>';
			$icon_size = 'fs-lg';
		} elseif ( 'grid-podcast' === $view ) {
			$before    = '<div class="d-flex align-items-center text-muted">';
			$after     = '</div>';
			$icon_size = 'fs-lg';
		} elseif ( 'featured' === $view ) {
			$before     = '<div class="d-flex align-items-center mb-2 text-nowrap">';
			$after      = '</div>';
			$icon_size  = 'fs-lg';
			$wrap_class = 'd-flex align-items-center me-3 opacity-70';
		} elseif ( 'slider-article' === $view ) {
			$icon_size  = 'fs-lg';
			$wrap_class = 'd-flex align-items-center me-3';
		} elseif ( 'card-list' === $view ) {
			$icon_size  = 'fs-lg';
			$wrap_class = 'd-flex align-items-center me-3';
			$before     = '<div class="d-flex align-items-center mt-sm-0 mt-4 text-muted">';
			$after      = '</div>';
		}

		if ( ! empty( $icon_items ) ) {

			$meta_icons = $before;

			foreach ( $icon_items as $icon_item ) :
				$icon_class = trim( $icon_item['icon'] . ' ' . $icon_size . ' me-1' );
				if ( ! empty( $icon_item['url'] ) ) {
					$meta_icons .= '<a href="' . esc_url( $icon_item['url'] ) . '" class="' . esc_attr( $wrap_class ) . ' text-reset text-decoration-none"><i class="' . esc_attr( $icon_class ) . '"></i><span class="fs-sm">' . esc_html( $icon_item['value'] ) . '</span></a>';
				} else {
					$meta_icons .= '<div class="' . esc_attr( $wrap_class ) . '"><i class="' . esc_attr( $icon_class ) . '"></i><span class="fs-sm">' . esc_html( $icon_item['value'] ) . '</span></div>';
				}
			endforeach;

			$meta_icons .= $after;

			echo wp_kses_post( $meta_icons );
		}
	}
}

if ( ! function_exists( 'silicon_the_post_categories' ) ) {
	/**
	 * Displays the post categories.
	 *
	 * @param string $view Optional. The view of the loop post.
	 */
	function silicon_the_post_categories( $view = 'default' ) {
		// Use this filter to override the main category using ACF.
		$categories_list = apply_filters( 'silicon_get_category_list', get_the_category_list( ' ' ) );
		if ( $categories_list ) {
			$should_wrap = true;
			$wrap_class  = 'mb-1';
			$bg_color    = apply_filters( 'silicon_cat_bg', 'bg-secondary' );
			$text_color  = 'text-nav';

			switch ( $view ) {
				case 'single':
					$bg_color      = 'bg-faded-primary dark:bg-secondary';
					$text_color    = 'text-primary dark:text-nav';
					$replace_class = 'badge fs-base me-1 mb-2';
					$wrap_class    = 'fs-xs border-end pe-3 me-3';
					break;
				case 'grid-v1':
					$replace_class = 'badge fs-sm text-decoration-none';
					$wrap_class    = 'mb-0';
					break;
				case 'grid-v2':
					$bg_color      = 'bg-secondary';
					$replace_class = 'badge fs-sm text-decoration-none';
					$wrap_class    = 'mb-0';
					break;
				case 'grid-v3':
					$replace_class = 'badge fs-sm text-decoration-none position-relative zindex-2';
					$wrap_class    = 'mb-0';
					break;
				case 'grid-v4':
					$bg_color      = 'bg-secondary';
					$text_color    = 'text-nav';
					$replace_class = 'badge fs-sm text-decoration-none position-relative zindex-2';
					$wrap_class    = 'mb-0';
					break;
				case 'grid-podcast':
					$bg_color      = 'bg-secondary';
					$text_color    = 'text-nav';
					$replace_class = 'badge fs-sm text-decoration-none';
					$wrap_class    = 'mb-0';
					break;
				case 'list-v1-odd':
					$replace_class = 'badge fs-sm text-decoration-none mb-3';
					$wrap_class    = 'mb-0';
					break;
				case 'list-v1-even':
					$replace_class = 'badge fs-sm text-decoration-none';
					$wrap_class    = 'mb-0';
					break;
				case 'list-v2':
					$bg_color      = 'bg-secondary';
					$text_color    = 'text-nav';
					$replace_class = 'badge fs-sm text-decoration-none';
					$wrap_class    = 'mb-0';
					break;
				case 'featured':
					$bg_color      = 'bg-faded-light';
					$text_color    = 'text-white';
					$replace_class = 'badge fs-base';
					$wrap_class    = 'border-end border-light h-100 mb-2 pe-3 me-3';
					break;
				case 'slider-article':
					$bg_color      = 'bg-white';
					$replace_class = 'badge fs-sm text-decoration-none position-relative zindex-2';
					$wrap_class    = 'mb-0';
					break;
				case 'card-grid':
				case 'card-list':
					$bg_color      = 'bg-secondary';
					$replace_class = 'badge fs-sm text-decoration-none';
					$wrap_class    = 'mb-0';
					break;
				case 'default':
				default:
					$bg_color      = 'bg-secondary';
					$replace_class = 'badge fs-sm text-decoration-none me-1 mb-2';
					break;
			}
			if ( ! in_array( $bg_color, array( 'bg-secondary', 'bg-light', 'bg-white' ), true ) && 'text-nav' === $text_color ) {
				$text_color  = 'text-white';
				$text_color .= ' ' . str_replace( 'bg-', 'shadow-', $bg_color );
			}

			$replace_class = $replace_class . ' ' . $bg_color . ' ' . $text_color;

			$replace  = 'class="' . esc_attr( trim( $replace_class ) ) . '" rel="category';
			$find     = 'rel="category';
			$the_list = str_replace( $find, $replace, $categories_list );

			if ( $should_wrap ) {
				$the_list = '<div class="' . esc_attr( $wrap_class ) . '">' . $the_list . '</div>';
			}

			echo wp_kses_post( $the_list );
		}
	}
}

if ( ! function_exists( 'silicon_the_post_date' ) ) {
	/**
	 * Display the post date
	 *
	 * @param string $view  The view for which the date is displayed.
	 * @param bool   $human Should display in human readable format.
	 */
	function silicon_the_post_date( $view = 'list-view', $human = false ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published d-none" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$post_date          = get_the_date();
		$post_modified_date = get_the_modified_date();

		if ( $human ) {
			/* translators: %$ - Time ago */
			$post_date = sprintf( esc_html__( '%s ago', 'silicon' ), human_time_diff( get_the_time( 'U' ) ) );
			/* translators: %$ - Time ago */
			$post_modified_date = sprintf( esc_html__( '%s ago', 'silicon' ), human_time_diff( get_the_modified_time( 'U' ) ) );
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( $post_date ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( $post_modified_date )
		);

		$link_class = 'text-decoration-none text-reset';

		/* translators: %1$s - Post link, %2$s - Post date time, %3$s - link class */
		$output_time_string = sprintf( '<a href="%1$s" rel="bookmark" class="%3$s">%2$s</a>', esc_url( get_permalink() ), $time_string, esc_attr( $link_class ) );
		$allowed_tags       = array(
			'span' => array(
				'class' => array(),
			),
			'a'    => array(
				'href'  => array(),
				'title' => array(),
				'rel'   => array(),
				'class' => array(),
			),
			'time' => array(
				'datetime' => array(),
				'class'    => array(),
			),
			'div'  => array(
				'class' => array(),
			),
		);

		$before = '';
		$after  = '';

		switch ( $view ) {
			case 'list-view':
				$before = '<span class="posted-on fs-sm text-muted border-start ps-3 ms-3">';
				$after  = '</span>';
				break;
			case 'list-view-v1':
			case 'grid-view-v1':
				$before = '<div class="fs-sm border-end pe-3 me-3">';
				$after  = '</div>';
				break;
			case 'grid-v2':
			case 'grid-v3':
				$before = '<span class="posted-on fs-sm text-muted">';
				$after  = '</span>';
				break;
			case 'grid-podcast':
				$before = '<span class="fs-sm text-muted border-start ps-3 ms-3">';
				$after  = '</span>';
				break;
			case 'single':
				$before = '<div class="fs-sm border-end pe-3 me-3 mb-2">';
				$after  = '</div>';
				break;
			case 'featured':
				$before = '<div class="border-end border-light mb-2 pe-3 me-3 opacity-70">';
				$after  = '</div>';
				break;
			case 'slider-article':
				$before = '<span class="fs-sm text-muted">';
				$after  = '</span>';
				break;
			case 'card-list':
				$before = '<span class="fs-sm text-muted border-start ps-3 ms-3">';
				$after  = '</span>';
				break;
			case 'card-grid':
				$before = '<span class="fs-sm text-muted">';
				$after  = '</span>';
				break;
			case 'list-simple':
				$before = '<div class="fs-sm border-end pe-3 me-3">';
				$after  = '</div>';
				break;
			case 'slider':
				$before = '<div class="badge fs-sm bg-secondary text-decoration-none">';
				$after  = '</div>';
				break;
			case 'default':
				$before = '<div class="fs-sm border-end pe-3 me-3">';
				$after  = '</div>';
				break;
		}

		echo wp_kses( $before . $output_time_string . $after, $allowed_tags );
	}
}

if ( ! function_exists( 'silicon_the_post_duration' ) ) {
	/**
	 * Display the duration of the post.
	 */
	function silicon_the_post_duration() {
		if ( 'audio' === get_post_format() ) :
			$duration = apply_filters( 'silicon_post_duration_text', '' );
			if ( ! empty( $duration ) ) :
				?>
			<span class="badge bg-dark position-absolute bottom-0 end-0 zindex-2 mb-3 me-3"><?php echo esc_html( $duration ); ?></span>
				<?php
			endif;
		endif;
	}
}

if ( ! function_exists( 'silicon_the_post_author' ) ) {
	/**
	 * Display the post author
	 *
	 * @param string $view The view for which the author is displayed.
	 * @param array  $args Arguments for generating the post author.
	 */
	function silicon_the_post_author( $view = 'list-view', $args = array() ) {
		$author_id  = get_the_author_meta( 'ID' );
		$author_url = get_author_posts_url( $author_id );

		if ( 'single-post' === $view ) :
			$args['before']     = '<div class="d-flex align-items-center position-relative ps-md-3 pe-lg-5 mb-2">';
			$args['before']    .= get_avatar( $author_id, 60, '', esc_html__( 'Avatar', 'silicon' ), array( 'class' => 'rounded-circle' ) );
			$args['before']    .= '<div class="ps-3">';
			$args['before']    .= '<h6 class="mb-1">' . esc_html__( 'Author', 'silicon' ) . '</h6>';
			$args['class_link'] = 'fw-semibold stretched-link url fn';
			$args['has_avatar'] = false;
			$args['after']      = '</div></div>';
		elseif ( 'card-list' === $view || 'card-grid' === $view ) :
			$args['before']     = '<div class="d-flex align-items-center position-relative me-3">';
			$args['before']    .= get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array( 'class' => 'rounded-circle me-3' ) );
			$args['before']    .= '<div>';
			$args['class_link'] = 'nav-link p-0 fw-bold text-decoration-none stretched-link';
			$args['after']      = '<span class="fs-sm text-muted">' . get_the_author_meta( 'description' ) . '</span></div></div>';
			$args['has_avatar'] = false;
		endif;

		$defaults = array(
			'before'        => '',
			'class_link'    => 'd-flex align-items-center fw-bold text-dark text-decoration-none me-3 url fn',
			'class_avatar'  => 'rounded-circle me-3',
			'gravatar_size' => 48,
			'has_avatar'    => true,
			'after'         => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$post_author  = $args['before'];
		$post_author .= '<a href="' . esc_url( $author_url ) . '" class="' . esc_attr( $args['class_link'] ) . '" rel="author">';

		if ( $args['has_avatar'] ) {
			$post_author .= get_avatar( $author_id, $args['gravatar_size'], '', esc_html__( 'Avatar', 'silicon' ), array( 'class' => $args['class_avatar'] ) );
		}

		$post_author .= get_the_author();
		$post_author .= '</a>';
		$post_author .= $args['after'];

		echo wp_kses_post( apply_filters( 'silicon_post_author_html', $post_author ) );
	}
}

if ( ! function_exists( 'grid_blog_search_form' ) ) {
	/**
	 * Grid blog search form.
	 */
	function grid_blog_search_form() {
		$blog  = apply_filters( 'display_grid_blog_search_form', false );
		$title = apply_filters( 'grid_blog_search_form_title', 'Blog' );
		if ( ! is_home() || ! $blog ) {
			return;
		}
		?>
		<div class="d-flex mt-4 pt-lg-2 align-items-center justify-content-between gy-3 mb-4 pb-lg-3 pb-1">
			<div class="col-lg-5 col-sm-6">
				<h1 class="page-title mb-0"><?php echo esc_html( $title ); ?></h1>
			</div>
			<?php grid_dropdown_category_filter(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'grid_dropdown_category_filter' ) ) {
	/**
	 * Grid dropdown filter category.
	 */
	function grid_dropdown_category_filter() {
		$enable = apply_filters( 'display_grid_dropdown_category_filter', false );
		if ( ! $enable ) {
			return;
		}
		?>
		<div class="row">
			<div class="col-lg-5 col-sm-6">
				<?php blog_categories_dropdown(); ?>
			</div>
			<div class="col-lg-7 col-sm-6">
				<?php blog_search_form(); ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'blog_search_form' ) ) {
	/**
	 * Blog search form.
	 */
	function blog_search_form() {
		?>
		<form role="search" method="get" class="search-form" action="<?php echo esc_url( get_site_url() ); ?>">
					<div class="input-group">
						<input type="search" class="form-control pe-5 rounded" placeholder="Search …" value="" name="s" title="Search for:">
						<i class="bx bx-search position-absolute top-50 end-0 translate-middle-y me-3 zindex-5 fs-lg"></i>
					</div>
					<input type="hidden" name="post_type" value="post">
				</form>
		<?php
	}
}

if ( ! function_exists( 'blog_categories_dropdown' ) ) {
	/**
	 * Blog categories dropdown.
	 */
	function blog_categories_dropdown() {
		$categories = get_categories(
			array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
			)
		);
		?>
		<form action="<?php echo esc_url( get_site_url() ); ?>" method="get"><label class="screen-reader-text" for="cat">Categories</label>
			<select name="cat" id="cat" class="postform me-5">
				<option value="-1">Select Category</option>
				<?php
				foreach ( $categories as $category ) {
					?>
					<option class="level-0" value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
					<?php
				}
				?>
			</select>
		</form>
		<script>
			/* <![CDATA[ */
			(function() {
				var dropdown = document.getElementById( "cat" );
				function onCatChange() {
					if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
						dropdown.parentNode.submit();
					}
				}
				dropdown.onchange = onCatChange;
			})();
			/* ]]&gt; */
		</script>
		<?php
	}
}
