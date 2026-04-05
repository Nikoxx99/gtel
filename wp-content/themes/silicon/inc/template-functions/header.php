<?php
/**
 * Template functions related to the Header.
 *
 * @package silicon
 */

if ( ! function_exists( 'silicon_masthead_class' ) ) {
	/**
	 * Displays the class names of the #masthead element.
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 */
	function silicon_masthead_class( $class = '' ) {
		// Separates class names with a single space, collates class names for body element.
		echo 'class="' . esc_attr( implode( ' ', silicon_get_masthead_class( $class ) ) ) . '"';
	}
}

if ( ! function_exists( 'silicon_get_masthead_class' ) ) {
	/**
	 * Retrieves an array of the class names for the body element.
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 * @return string[] Array of class names.
	 */
	function silicon_get_masthead_class( $class = '' ) {
		$classes = array( 'site-header', 'header', 'navbar', 'navbar-expand-lg' );

		// Customizable Sticky options.
		if ( silicon_custom_sticky_header_options() ) {
			$classes = array_merge( $classes, explode( ' ', silicon_custom_sticky_header_options() ) );
		}

		$fixed_classes = array( 'position-absolute', 'fixed-top' );
		if ( is_admin_bar_showing() && ! empty( array_intersect( $fixed_classes, $classes ) ) ) {
			$classes[] = 'top-[32px]';
			$classes[] = 'h-[76px]';
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = array_map( 'esc_attr', $classes );

		/**
		 * Filters the list of CSS #masthead class names for the current post or page.
		 *
		 * @param string[] $classes An array of #masthead class names.
		 * @param string[] $class   An array of additional class names added to the #masthead.
		 */
		$classes = apply_filters( 'silicon_masthead_class', $classes, $class );

		return array_unique( $classes );
	}
}

if ( ! function_exists( 'silicon_content_container_class' ) ) {
	/**
	 * Displays the class names of the .content-container element.
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 */
	function silicon_content_container_class( $class = '' ) {
		// Separates class names with a single space, collates class names for body element.
		echo 'class="' . esc_attr( implode( ' ', silicon_get_content_container_class( $class ) ) ) . '"';
	}
}

if ( ! function_exists( 'silicon_get_content_container_class' ) ) {
	/**
	 * Retrieves an array of the class names for the .content-area element.
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 *
	 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
	 * @return string[] Array of class names.
	 */
	function silicon_get_content_container_class( $class = '' ) {
		$classes          = array( 'content__container', 'container' );
		$masthead_classes = silicon_get_masthead_class();

		if ( in_array( 'fixed-top', $masthead_classes, true ) ) {
			$classes[] = 'mt-lg-4';
			$classes[] = 'pt-5';
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = array_map( 'esc_attr', $classes );

		$classes = apply_filters( 'silicon_content_container_class', $classes, $class );

		return $classes;
	}
}

if ( ! function_exists( 'silicon_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @return void
	 */
	function silicon_site_branding() {
		silicon_site_dark_logo();
		silicon_site_title_or_logo();
	}
}

if ( ! function_exists( 'silicon_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @param bool $echo Echo the string or return it.
	 * @return string
	 */
	function silicon_site_title_or_logo( $echo = true ) {
		$logo = '';
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo = get_custom_logo();
		}

		$logo_text = get_bloginfo( 'name' );

		$is_logo_and_text_enabled = apply_filters( 'silicon_icon_and_text_enabled', true );
		if ( ! $is_logo_and_text_enabled ) {
			$logo_text = '';
		}
		$html = str_replace( 'custom-logo-link', 'site-light-logo navbar-brand pe-3', $logo );
		if ( $logo && $logo_text ) {
			$html = str_replace( '</a>', $logo_text . '</a>', $html );
		}
		if ( empty( $logo ) && $logo_text ) {
			$html = '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-light-logo navbar-brand pe-3">' . $logo_text . '</a>';
		}

		if ( ! $echo ) {
			return $html;
		}

		echo apply_filters( 'silicon_site_title', $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}


if ( ! function_exists( 'silicon_site_dark_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @param bool $echo Echo the string or return it.
	 * @return string
	 */
	function silicon_site_dark_logo( $echo = true ) {
		$dark_logo = wp_get_attachment_image(
			get_theme_mod( 'dark_logo' ),
			'full',
			false,
			array(
				'class' => 'site-dark-logo',
			)
		);

		$logo_text                = get_bloginfo( 'name' );
		$is_logo_and_text_enabled = apply_filters( 'silicon_icon_and_text_enabled', true );
		if ( ! $is_logo_and_text_enabled ) {
			$logo_text = '';
		}
		$html = '';
		if ( ! empty( get_theme_mod( 'dark_logo' ) ) && ! $logo_text ) {
			$html = '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-dark-logo navbar-brand pe-3">' . $dark_logo . '</a>';
		}

		if ( ! empty( get_theme_mod( 'dark_logo' ) ) && $logo_text ) {
			$html = '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-dark-logo navbar-brand pe-3">' . $dark_logo . $logo_text . '</a>';
		}

		if ( empty( get_theme_mod( 'dark_logo' ) ) && $logo_text ) {
			$html = '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-dark-logo navbar-brand pe-3">' . $logo_text . '</a>';
		}

		if ( ! $echo ) {
			return $html;
		}

		echo apply_filters( 'silicon_dark_site_title', $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'silicon_mode_switcher' ) ) {
	/**
	 * Display Silicon Mode switcher.
	 */
	function silicon_mode_switcher() {
		$enabled      = silicon_enable_mode_switcher();
		$header_class = silicon_custom_sticky_header_options();
		if ( strpos( $header_class, 'navbar-dark' ) ) {
			?>
			<div class="dark-mode pe-lg-1 ms-auto me-4">
			<?php
		}

		if ( true === $enabled ) :
			?>
			<div class="form-check form-switch mode-switch pe-lg-1 ms-auto me-4" data-bs-toggle="mode">
				<input type="checkbox" class="form-check-input" id="theme-mode">
				<label class="form-check-label d-none d-sm-block" for="theme-mode"><?php echo esc_html__( 'Light', 'silicon' ); ?></label>
				<label class="form-check-label d-none d-sm-block" for="theme-mode"><?php echo esc_html__( 'Dark', 'silicon' ); ?></label>
			</div>
			<?php
		endif;
		if ( strpos( $header_class, 'navbar-dark' ) ) {
			?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'silicon_navbar_toggler' ) ) {
	/**
	 * Navbar Toggler.
	 */
	function silicon_navbar_toggler() {
		?>
		<button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php echo esc_attr__( 'Toggle navigation', 'silicon' ); ?>"><span class="navbar-toggler-icon"></span></button>
		<?php
	}
}

if ( ! function_exists( 'silicon_navbar_action_btn' ) ) {
	/**
	 * Display an action button in the navbar..
	 */
	function silicon_navbar_action_btn() {
		if ( apply_filters( 'silicon_navbar_action_btn_enabled', false ) ) {
			$action_btn_link  = apply_filters( 'navbar_action_btn_link', '#' );
			$action_btn_text  = apply_filters( 'navbar_action_btn_text', esc_html__( 'Buy now', 'silicon' ) );
			$action_btn_icon  = apply_filters( 'navbar_action_btn_icon', 'bx bx-cart' );
			$action_btn_class = apply_filters( 'navbar_action_btn_class', 'btn btn-primary btn-sm fs-sm rounded' );
			?>
			<a href="<?php echo esc_url( $action_btn_link ); ?>" class="<?php echo esc_attr( $action_btn_class ); ?> d-none d-lg-inline-flex">
				<?php if ( ! empty( $action_btn_icon ) ) : ?>
					<i class="<?php echo esc_attr( $action_btn_icon ); ?> fs-5 lh-1 me-1"></i>&nbsp;<?php endif; ?><?php echo esc_html( $action_btn_text ); ?>
			</a>
			<?php
		}
	}
}

if ( ! function_exists( 'silicon_elementor_control_options' ) ) {
	/**
	 * Returns Elementor Controls.
	 *
	 * @return array
	 */
	function silicon_elementor_control_options() {

		$sn_page_options = array();

		if ( function_exists( 'silicon_option_enabled_post_types' ) && is_singular( silicon_option_enabled_post_types() ) ) {
			$clean_meta_data  = get_post_meta( get_the_ID(), '_sn_page_options', true );
			$_sn_page_options = maybe_unserialize( $clean_meta_data );

			if ( is_array( $_sn_page_options ) ) {
				$sn_page_options = $_sn_page_options;
			}
		}
		return $sn_page_options;
	}
}

if ( ! function_exists( 'silicon_custom_sticky_header_options' ) ) {
	/**
	 * Returns Header Class.
	 *
	 * @return string
	 */
	function silicon_custom_sticky_header_options() {
		$sn_page_options = silicon_elementor_control_options();
		$enable_header   = isset( $sn_page_options['header']['silicon_enable_custom_header'] ) ? $sn_page_options['header']['silicon_enable_custom_header'] : '';

		if ( 'yes' === $enable_header ) {
			$header_class = '';

			$navbar_position = isset( $sn_page_options['header']['silicon_select_navbar_position'] ) ? $sn_page_options['header']['silicon_select_navbar_position'] : '';
			$sticky_class    = isset( $sn_page_options['header']['silicon_enable_sticky'] ) ? $sn_page_options['header']['silicon_enable_sticky'] : '';
			$sticky_class    = 'yes' === $sticky_class ? true : false;
			$navbar_text     = isset( $sn_page_options['header']['silicon_select_navbar_text'] ) ? $sn_page_options['header']['silicon_select_navbar_text'] : '';
			$navbar_text     = 'yes' === $navbar_text ? 'dark' : 'light';
			$bg_class        = isset( $sn_page_options['header']['silicon_select_background'] ) ? $sn_page_options['header']['silicon_select_background'] : '';

			$shadow_class        = isset( $sn_page_options['header']['silicon_enable_shadow'] ) ? $sn_page_options['header']['silicon_enable_shadow'] : '';
			$shadow_class        = 'yes' === $shadow_class ? true : '';
			$disable_dark_shadow = isset( $sn_page_options['header']['silicon_disable_dark_shadow'] ) ? $sn_page_options['header']['silicon_disable_dark_shadow'] : '';
			$disable_dark_shadow = 'yes' === $disable_dark_shadow ? true : '';
			$border_class        = isset( $sn_page_options['header']['silicon_border'] ) ? $sn_page_options['header']['silicon_border'] : '';
			$border_color        = isset( $sn_page_options['header']['silicon_border_color'] ) ? $sn_page_options['header']['silicon_border_color'] : '';

			if ( 'none' !== $border_class && ! empty( $border_class ) ) {
				$header_class .= ' border-' . $border_class;
				$header_class .= ' border-' . $border_color;
			}
		} elseif ( function_exists( 'silicon_acf_is_enable_header' ) && silicon_acf_is_enable_header() && is_single() ) {
			$header_class        = '';
			$shadow_class        = silicon_acf_is_enable_shadow();
			$disable_dark_shadow = silicon_acf_is_disable_dark_mode_shadow();
			$border_class        = silicon_acf_is_border();
			$border_color        = silicon_acf_is_border_color();
			$sticky_class        = 'yes' === silicon_acf_is_enable_sticky() ? true : false;
			$navbar_position     = silicon_acf_is_enable_position();
			$navbar_text         = silicon_acf_is_enable_dark_navbar();
			$bg_class            = silicon_acf_is_enable_light_background();

			if ( 'none' !== $border_class && ! empty( $border_class ) ) {
				$header_class .= ' border-' . $border_class;
				$header_class .= ' border-' . $border_color;
			}
		} else {
			$header_class        = '';
			$shadow_class        = 'yes' === get_theme_mod( 'enable_silicon_header_shadow', 'yes' ) ? true : false;
			$disable_dark_shadow = 'yes' === get_theme_mod( 'disable_silicon_header_dark_shadow', 'no' ) ? true : false;
			$border_class        = get_theme_mod( 'silicon_header_border', 'none' );
			$border_color        = get_theme_mod( 'silicon_header_border_color', 'light' );
			$navbar_position     = get_theme_mod( 'silicon_select_header_navbar_position', 'default' );
			$sticky_class        = 'yes' === get_theme_mod( 'enable_silicon_header_sticky', 'no' ) ? true : false;
			$navbar_text         = get_theme_mod( 'silicon_header_navbar_text', 'light' );
			$bg_class            = get_theme_mod( 'silicon_select_header_background', 'default' );

			if ( 'none' !== $border_class && ! empty( $border_class ) ) {
				$header_class .= ' border-' . $border_class;
				$header_class .= ' border-' . $border_color;
			}
		}

		$header_class .= 'default' !== $navbar_position ? ' ' . $navbar_position : '';
		$header_class .= true === $sticky_class && 'fixed-top' !== $navbar_position ? ' navbar-sticky' : '';
		$header_class .= 'light' === $navbar_text ? ' navbar-light' : ' navbar-dark';
		$header_class .= 'default' !== $bg_class ? ' ' . $bg_class : '';

		if ( $shadow_class && ! is_404() && ! is_single() ) {
			$header_class .= $shadow_class ? ' shadow-sm' : '';
			$header_class .= $disable_dark_shadow ? ' shadow-dark-mode-none' : '';
		}
		return $header_class;
	}
}

if ( ! function_exists( 'silicon_custom_button_options' ) ) {
	/**
	 * Print Header Button.
	 *
	 * @return array
	 */
	function silicon_custom_button_options() {
		$sn_page_options = silicon_elementor_control_options();

		$enable_button = isset( $sn_page_options['header']['silicon_enable_primary_nav_button'] ) ? $sn_page_options['header']['silicon_enable_primary_nav_button'] : '';
		$enable_header = isset( $sn_page_options['header']['silicon_enable_custom_header'] ) ? $sn_page_options['header']['silicon_enable_custom_header'] : '';
		if ( $enable_button && 'yes' === $enable_header ) {
			$action_btn_class  = 'sn-header-button btn';
			$action_btn_class .= isset( $sn_page_options['header']['silicon_buy_button_type'] ) ? ' btn-' . $sn_page_options['header']['silicon_buy_button_type'] : ' btn-primary';
			$action_btn_class .= isset( $sn_page_options['header']['silicon_buy_button_size'] ) ? ' btn-' . $sn_page_options['header']['silicon_buy_button_size'] : ' btn-sm';
			$action_btn_class .= isset( $sn_page_options['header']['silicon_buy_button_shape'] ) ? ' ' . $sn_page_options['header']['silicon_buy_button_shape'] : ' rounded';
			$action_btn_class .= isset( $sn_page_options['header']['silicon_buy_button_css'] ) ? ' ' . $sn_page_options['header']['silicon_buy_button_css'] : '';
			$action_btn_icon   = isset( $sn_page_options['header']['silicon_buy_button_icon']['value'] ) ? $sn_page_options['header']['silicon_buy_button_icon']['value'] : '';
			$action_btn_text   = isset( $sn_page_options['header']['silicon_buy_button_text'] ) ? $sn_page_options['header']['silicon_buy_button_text'] : '';
			$action_btn_link   = isset( $sn_page_options['header']['silicon_buy_button_link'] ) ? $sn_page_options['header']['silicon_buy_button_link'] : '';
			$button_enabled    = ( $enable_button && 'yes' === $enable_header ) ? true : false;
		} elseif ( function_exists( 'silicon_acf_is_enable_header' ) && silicon_acf_is_enable_header() && silicon_acf_is_enable_buy_now_button() ) {
			$action_btn_class  = 'btn';
			$action_btn_class .= ' btn-' . silicon_acf_is_buy_now_button_color();
			$action_btn_class .= silicon_acf_is_buy_now_button_size() ? ' btn-' . silicon_acf_is_buy_now_button_size() : '';
			$action_btn_class .= ' ' . silicon_acf_is_buy_now_button_shape();
			$action_btn_class .= silicon_acf_is_buy_now_button_css() ? ' ' . silicon_acf_is_buy_now_button_css() : '';
			$action_btn_icon   = silicon_acf_is_buy_now_button_icon();
			$action_btn_text   = silicon_acf_is_buy_now_button_text();
			$action_btn_link   = silicon_acf_is_buy_now_button_link();
			$button_enabled    = ( silicon_acf_is_enable_header() && silicon_acf_is_enable_buy_now_button() ) ? true : false;
		} else {
			$action_btn_class  = 'btn';
			$action_btn_class .= ' btn-' . get_theme_mod( 'header_button_color', 'primary' );
			$action_btn_class .= get_theme_mod( 'silicon_header_button_size', 'sm' ) ? ' btn-' . get_theme_mod( 'silicon_header_button_size', 'sm' ) : '';
			$action_btn_class .= ' ' . get_theme_mod( 'silicon_header_button_shape', 'rounded' );
			$action_btn_class .= get_theme_mod( 'silicon_header_button_css', '' ) ? ' ' . get_theme_mod( 'silicon_header_button_css', '' ) : '';
			$action_btn_icon   = get_theme_mod( 'silicon_button_icon', 'bx bx-cart' );
			$action_btn_text   = get_theme_mod( 'header_button_text', 'Buy Now' );
			$action_btn_link   = get_theme_mod( 'header_button_url', '#' );
			$button_enabled    = 'yes' === get_theme_mod( 'enable_primary_nav_button', 'no' ) ? true : false;
		}
		$button_args = [
			'class'   => $action_btn_class,
			'link'    => $action_btn_link,
			'text'    => $action_btn_text,
			'icon'    => $action_btn_icon,
			'enabled' => $button_enabled,
		];
		return $button_args;

	}
}

if ( ! function_exists( 'silicon_custom_button' ) ) {
	/**
	 * Print Header Button.
	 *
	 * @return void
	 */
	function silicon_custom_button() {
		$btn_args = silicon_custom_button_options();
		if ( $btn_args['enabled'] ) {
			?>
			<a href="<?php echo esc_url( $btn_args['link'] ); ?>" class="<?php echo esc_attr( $btn_args['class'] ); ?> d-none d-lg-inline-flex">
				<?php if ( ! empty( $btn_args['icon'] ) ) : ?>
					<i class="<?php echo esc_attr( $btn_args['icon'] ); ?> fs-5 lh-1 me-1"></i>&nbsp;<?php endif; ?><?php echo esc_html( $btn_args['text'] ); ?>
			</a>
			<?php
		}
	}
}

if ( ! function_exists( 'silicon_toggle_custom_button_options' ) ) {
	/**
	 * Print Header Button.
	 *
	 * @return void
	 */
	function silicon_toggle_custom_button_options() {

		$btn_args = silicon_custom_button_options();

		if ( $btn_args['enabled'] ) {
			?>
			<a href="<?php echo esc_url( $btn_args['link'] ); ?>" class="<?php echo esc_attr( $btn_args['class'] ); ?> w-100">
				<?php if ( ! empty( $btn_args['icon'] ) ) : ?>
					<i class="<?php echo esc_attr( $btn_args['icon'] ); ?> fs-4 lh-1 me-1"></i>&nbsp;<?php endif; ?><?php echo esc_html( $btn_args['text'] ); ?>
			</a>
			<?php
		}
	}
}

if ( ! function_exists( 'silicon_navbar_nav' ) ) {
	/**
	 * Returns Elementor Controls.
	 *
	 * @return void
	 */
	function silicon_navbar_nav() {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'navbar-nav me-auto mb-2 mb-lg-0',
				'walker'         => new WP_Bootstrap_Navwalker(),
				'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback',
			)
		);
	}
}

if ( ! function_exists( 'silicon_header_navbar_offcanvas' ) ) {
	/**
	 * Returns Elementor Controls.
	 *
	 * @return void
	 */
	function silicon_header_navbar_offcanvas() {
		$header_class = silicon_custom_sticky_header_options();
		$dark_css     = strpos( $header_class, 'navbar-dark' ) ? ' bg-dark' : '';
		?>
		<div id="navbarNav" class="offcanvas offcanvas-end<?php echo esc_attr( $dark_css ); ?>">
			<div class="offcanvas-header border-bottom">
				<h5 class="offcanvas-title"><?php echo esc_html__( 'Menu', 'silicon' ); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>
			<div class="offcanvas-body">
				<?php silicon_navbar_nav(); ?>
			</div>
			<div class="offcanvas-footer border-top">
			<?php silicon_toggle_custom_button_options(); ?>
			</div>      
		</div>
		<?php
	}
}

if ( ! function_exists( 'silicon_enable_mode_switcher' ) ) {
	/**
	 * Display Silicon Mode switcher.
	 */
	function silicon_enable_mode_switcher() {
		$value                = get_theme_mod( 'enable_silicon_mode_switcher', 'yes' );
		$enable_mode_switcher = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		return apply_filters( 'silicon_enable_mode_switcher', $enable_mode_switcher );
	}
}

if ( ! function_exists( 'silicon_default_mode' ) ) {
	/**
	 * Display Silicon Mode switcher.
	 */
	function silicon_default_mode() {
		$value       = get_theme_mod( 'silicon_default_mode', 'no' );
		$enable_mode = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		return apply_filters( 'silicon_default_mode', $enable_mode );
	}
}
