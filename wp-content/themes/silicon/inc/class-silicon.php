<?php
/**
 * Silicon Class
 *
 * @since    2.0.0
 * @package  silicon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Silicon' ) ) :

	/**
	 * The main Silicon class
	 */
	class Silicon {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'inline_scripts' ), 0 );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 20 );
			add_action( 'wp_enqueue_scripts', array( $this, 'child_scripts' ), 30 );
			add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
			add_filter( 'body_class', array( $this, 'body_classes' ) );
			add_filter( 'wp_page_menu_args', array( $this, 'page_menu_args' ) );
			add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ) );
			add_action( 'enqueue_embed_scripts', array( $this, 'print_embed_styles' ) );
			add_filter( 'big_image_size_threshold', '__return_false' );
			add_filter( 'comment_form_default_fields', array( $this, 'comment_form_fields' ) );
			add_action( 'comment_form_after', array( $this, 'output_comment_after' ) );
			add_filter( 'silicon_header_has_shadow', array( $this, 'has_shadow' ) );
			add_filter( 'do_shortcode_tag', array( $this, 'build_elementor_content' ), 10, 3 );
			add_filter( 'the_password_form', array( $this, 'style_password_form' ), 10, 2 );
			add_filter( 'language_attributes', array( $this, 'set_site_mode' ), 10, 2 );
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			/*
			 * Load Localisation files.
			 *
			 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
			 */

			// Loads wp-content/languages/themes/silicon-it_IT.mo.
			load_theme_textdomain( 'silicon', trailingslashit( WP_LANG_DIR ) . 'themes' );

			// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
			load_theme_textdomain( 'silicon', get_stylesheet_directory() . '/languages' );

			// Loads wp-content/themes/silicon/languages/it_IT.mo.
			load_theme_textdomain( 'silicon', get_template_directory() . '/languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );

			/**
			 * Add posts formats.
			 */
			add_theme_support( 'post-formats', array( 'audio' ) );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );

			/**
			 * Enable support for site logo.
			 */
			add_theme_support(
				'custom-logo',
				apply_filters(
					'silicon_custom_logo_args',
					array(
						'height'      => 60,
						'width'       => 145,
						'flex-width'  => true,
						'flex-height' => true,
					)
				)
			);

			/**
			 * Register menu locations.
			 */
			register_nav_menus(
				apply_filters(
					'silicon_register_nav_menus',
					array(
						'primary'   => esc_html__( 'Primary Menu', 'silicon' ),
						'portfolio' => esc_html__( 'Portfolio Menu', 'silicon' ),
					)
				)
			);

			/*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
			add_theme_support(
				'html5',
				apply_filters(
					'silicon_html5_args',
					array(
						'search-form',
						'comment-form',
						'comment-list',
						'gallery',
						'caption',
						'widgets',
						'style',
						'script',
					)
				)
			);

			/**
			 *  Add support for the Site Logo plugin and the site logo functionality in JetPack
			 *  https://github.com/automattic/site-logo
			 *  http://jetpack.me/
			 */
			add_theme_support(
				'site-logo',
				apply_filters(
					'silicon_site_logo_args',
					array(
						'size' => 'full',
					)
				)
			);

			/**
			 * Declare support for title theme feature.
			 */
			add_theme_support( 'title-tag' );

			/**
			 * Declare support for selective refreshing of widgets.
			 */
			add_theme_support( 'customize-selective-refresh-widgets' );

			/**
			 * Add support for Block Styles.
			 */
			add_theme_support( 'wp-block-styles' );

			/**
			 * Add support for full and wide align images.
			 */
			add_theme_support( 'align-wide' );

			/**
			 * Add support for editor styles.
			 */
			add_theme_support( 'editor-styles' );

			/**
			 * Add support for editor font sizes.
			 */
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name' => esc_html__( 'Extra Small', 'silicon' ),
						'size' => 12,
						'slug' => 'xs',
					),
					array(
						'name' => esc_html__( 'Small', 'silicon' ),
						'size' => 14,
						'slug' => 'sm',
					),
					array(
						'name' => esc_html__( 'Base', 'silicon' ),
						'size' => 16,
						'slug' => 'small',
					),
					array(
						'name' => esc_html__( 'Large', 'silicon' ),
						'size' => 18,
						'slug' => 'lg',
					),
					array(
						'name' => esc_html__( 'Extra Large', 'silicon' ),
						'size' => 20,
						'slug' => 'xl',
					),
				)
			);

			/**
			 * Add support for editor color palettes.
			 */
			add_theme_support(
				'editor-color-palette',
				array(
					array(
						'name'  => esc_html__( 'Primary', 'silicon' ),
						'slug'  => 'primary',
						'color' => '#6366f1',
					),
					array(
						'name'  => esc_html__( 'Secondary', 'silicon' ),
						'slug'  => 'secondary',
						'color' => '#eff2fc',
					),
					array(
						'name'  => esc_html__( 'Info', 'silicon' ),
						'slug'  => 'info',
						'color' => '#4c82f7',
					),
					array(
						'name'  => esc_html__( 'Success', 'silicon' ),
						'slug'  => 'success',
						'color' => '#22c55e',
					),
					array(
						'name'  => esc_html__( 'Warning', 'silicon' ),
						'slug'  => 'warning',
						'color' => '#ffba08',
					),
					array(
						'name'  => esc_html__( 'Danger', 'silicon' ),
						'slug'  => 'danger',
						'color' => '#ef4444',
					),
					array(
						'name'  => esc_html__( 'Light', 'silicon' ),
						'slug'  => 'light',
						'color' => '#ffffff',
					),
					array(
						'name'  => esc_html__( 'Dark', 'silicon' ),
						'slug'  => 'dark',
						'color' => '#131022',
					),
				)
			);

			/**
			 * Enqueue editor styles.
			 */
			add_editor_style( array( 'assets/css/gutenberg-editor.css', $this->google_fonts() ) );

			/**
			 * Add support for responsive embedded content.
			 */
			add_theme_support( 'responsive-embeds' );
		}

		/**
		 * Register widget area.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function widgets_init() {
			$sidebar_args['sidebar-blog'] = array(
				'name'        => esc_html__( 'Blog Sidebar', 'silicon' ),
				'id'          => 'sidebar-blog',
				'description' => '',
			);

			$rows    = intval( apply_filters( 'silicon_footer_widget_rows', 0 ) );
			$regions = intval( apply_filters( 'silicon_footer_widget_columns', 0 ) );

			for ( $row = 1; $row <= $rows; $row++ ) {
				for ( $region = 1; $region <= $regions; $region++ ) {
					$footer_n = $region + $regions * ( $row - 1 ); // Defines footer sidebar ID.
					$footer   = sprintf( 'footer_%d', $footer_n );

					if ( 1 === $rows ) {
						/* translators: 1: column number */
						$footer_region_name = sprintf( esc_html__( 'Footer Column %1$d', 'silicon' ), $region );

						/* translators: 1: column number */
						$footer_region_description = sprintf( esc_html__( 'Widgets added here will appear in column %1$d of the footer.', 'silicon' ), $region );
					} else {
						/* translators: 1: row number, 2: column number */
						$footer_region_name = sprintf( esc_html__( 'Footer Row %1$d - Column %2$d', 'silicon' ), $row, $region );

						/* translators: 1: column number, 2: row number */
						$footer_region_description = sprintf( esc_html__( 'Widgets added here will appear in column %1$d of footer row %2$d.', 'silicon' ), $region, $row );
					}

					$sidebar_args[ $footer ] = array(
						'name'        => $footer_region_name,
						'id'          => sprintf( 'footer-%d', $footer_n ),
						'description' => $footer_region_description,
					);
				}
			}

			$sidebar_args = apply_filters( 'silicon_sidebar_args', $sidebar_args );

			foreach ( $sidebar_args as $sidebar => $args ) {
				$widget_tags = array(
					'before_widget' => '<div id="%1$s" class="widget mb-4 %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="h5 widget-title">',
					'after_title'   => '</h3>',
				);

				/**
				 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
				 *
				 * 'silicon_header_widget_tags'
				 * 'silicon_sidebar_widget_tags'
				 *
				 * 'silicon_footer_1_widget_tags'
				 * 'silicon_footer_2_widget_tags'
				 * 'silicon_footer_3_widget_tags'
				 * 'silicon_footer_4_widget_tags'
				 */
				$filter_hook = sprintf( 'silicon_%s_widget_tags', $sidebar );
				$widget_tags = apply_filters( $filter_hook, $widget_tags );

				if ( is_array( $widget_tags ) ) {
					register_sidebar( $args + $widget_tags );
				}
			}
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @since  1.0.0
		 */
		public function scripts() {
			global $silicon_version;

			wp_localize_script(
				'silicon_global',
				'silicon_global_obj',
				array(
					'mode' => 'light',
				)
			);

			/**
			 * Styles
			 */

			// Vendor Styles.
			wp_enqueue_style( 'elementor-icons-box-icons', get_template_directory_uri() . '/assets/vendor/boxicons/css/boxicons.min.css', '', $silicon_version );
			wp_enqueue_style( 'elementor-light-gallery-video', get_template_directory_uri() . '/assets/vendor/lightgallery.js/src/css/lightgallery.css', '', $silicon_version );

			wp_enqueue_style( 'silicon-swiper-style', get_template_directory_uri() . '/assets/vendor/swiper/swiper-bundle.min.css', '', $silicon_version );
			wp_enqueue_style( 'silicon-img-comparision-slider-style', get_template_directory_uri() . '/assets/vendor/img-comparison-slider/dist/styles.css', '', $silicon_version );

			// Theme.
			wp_enqueue_style( 'silicon-style', get_template_directory_uri() . '/style.css', '', $silicon_version );

			/**
			 * Fonts
			 */
			wp_enqueue_style( 'silicon-fonts', $this->google_fonts(), array(), $silicon_version );

			/**
			 * Scripts
			 */

			$vendor_scripts = apply_filters(
				'silicon_vendor_scripts',
				array(
					'bootstrap-bundle' => array(
						'path'      => 'bootstrap/dist/js/bootstrap.bundle.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'smooth-scroll'    => array(
						'path'      => 'smooth-scroll/dist/smooth-scroll.polyfills.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'imagesloaded'     => array(
						'path'      => 'imagesloaded/imagesloaded.pkgd.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'jarallax'         => array(
						'path'      => 'jarallax/dist/jarallax.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'jarallax-element' => array(
						'path'      => 'jarallax/dist/jarallax-element.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'parallax'         => array(
						'path'      => 'parallax-js/dist/parallax.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'shufflejs'        => array(
						'path'      => 'shufflejs/dist/shuffle.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'lightgallery-js'  => array(
						'path'      => 'lightgallery.js/dist/js/lightgallery.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'lg-video-js'      => array(
						'path'      => 'lg-video.js/dist/lg-video.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'lg-thumbnail-js'  => array(
						'path'      => 'lg-thumbnail.js/dist/lg-thumbnail.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'swiper'           => array(
						'path'      => 'swiper/swiper-bundle.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
					'rellax'           => array(
						'path'      => 'rellax/rellax.min.js',
						'deps'      => array(),
						'in_footer' => true,
					),
				)
			);

			foreach ( $vendor_scripts as $handle => $vendor_script ) {
				wp_enqueue_script( $handle, get_template_directory_uri() . '/assets/vendor/' . $vendor_script['path'], $vendor_script['deps'], $silicon_version, $vendor_script['in_footer'] );
			}

			if ( is_404() ) {
				wp_enqueue_script( 'lottie-player', get_template_directory_uri() . '/assets/vendor/@lottiefiles/lottie-player/dist/lottie-player.js', array(), $silicon_version, true );
			}

			wp_enqueue_script( 'silicon-script', get_template_directory_uri() . '/assets/js/theme.min.js', array(), $silicon_version, true );

			wp_enqueue_script( 'silicon-navigation', get_template_directory_uri() . '/assets/js/components/navigation.js', array(), $silicon_version, true );
			wp_enqueue_script( 'silicon-navigation-init', get_template_directory_uri() . '/assets/js/components/navigation-init.js', array(), $silicon_version, true );

			if ( has_nav_menu( 'primary' ) ) {
				$silicon_l10n = array(
					'expand'   => esc_html__( 'Expand child menu', 'silicon' ),
					'collapse' => esc_html__( 'Collapse child menu', 'silicon' ),
				);

				wp_localize_script( 'silicon-navigation', 'siliconScreenReaderText', $silicon_l10n );
			}
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if ( apply_filters( 'silicon_use_predefined_colors', true ) && filter_var( get_theme_mod( 'silicon_enable_custom_color', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
				wp_enqueue_style( 'silicon-color', get_template_directory_uri() . '/assets/css/colors/color.css', '', $silicon_version );
			}

			if ( apply_filters( 'silicon_enable_system_mode', true ) && filter_var( get_theme_mod( 'silicon_enable_system_mode', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
				wp_enqueue_script( 'silicon-system-mode', get_template_directory_uri() . '/assets/js/components/system-mode-switch.js', array(), $silicon_version, true );
			}
		}

		/**
		 * Register Google fonts.
		 *
		 * @return string Google fonts URL for the theme.
		 */
		public function google_fonts() {
			$fonts_url = 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap';
			return $fonts_url;
		}

		/**
		 * Enqueue block assets.
		 *
		 * @since 2.5.0
		 */
		public function block_assets() {
			global $silicon_version;
		}

		/**
		 * Enqueue child theme stylesheet.
		 * A separate function is required as the child theme css needs to be enqueued _after_ the parent theme
		 * primary css.
		 *
		 * @since  1.5.3
		 */
		public function child_scripts() {
			if ( is_child_theme() ) {
				$child_theme = wp_get_theme( get_stylesheet() );
				wp_enqueue_style( 'silicon-child-style', get_stylesheet_uri(), array(), $child_theme->get( 'Version' ) );
			}
		}

		/**
		 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
		 *
		 * @param array $args Configuration arguments.
		 * @return array
		 */
		public function page_menu_args( $args ) {
			$args['show_home'] = true;
			return $args;
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @return array
		 */
		public function body_classes( $classes ) {
			// Adds a class to blogs with more than 1 published author.
			if ( is_multi_author() ) {
				$classes[] = 'group-blog';
			}

			// Add class when using homepage template + featured image.
			if ( is_page_template( 'template-homepage.php' ) && has_post_thumbnail() ) {
				$classes[] = 'has-post-thumbnail';
			}

			// Add class when Secondary Navigation is in use.
			if ( has_nav_menu( 'secondary' ) ) {
				$classes[] = 'silicon-secondary-navigation';
			}

			// Add class if align-wide is supported.
			if ( current_theme_supports( 'align-wide' ) ) {
				$classes[] = 'silicon-align-wide';
			}

			if ( true === apply_filters( 'silicon_prepend_hash', true ) ) {
				$classes[] = 'prepend-hash';
			}

			if ( filter_var( get_theme_mod( 'silicon_enable_custom_color', 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
				$classes[] = 'custom-color-scheme';
			}

			return $classes;
		}

		/**
		 * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
		 */
		public function navigation_markup_template() {
			$template  = '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="' . esc_html__( 'Post Navigation', 'silicon' ) . '">';
			$template .= '<h2 class="screen-reader-text">%2$s</h2>';
			$template .= '<div class="nav-links">%3$s</div>';
			$template .= '</nav>';

			return apply_filters( 'silicon_navigation_markup_template', $template );
		}

		/**
		 * Add styles for embeds
		 */
		public function print_embed_styles() {
			global $silicon_version;

			wp_enqueue_style( 'source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,700,900', array(), $silicon_version );
			$accent_color     = get_theme_mod( 'silicon_accent_color' );
			$background_color = silicon_get_content_background_color();
			?>
			<style type="text/css">
				.wp-embed {
					padding: 2.618em !important;
					border: 0 !important;
					border-radius: 3px !important;
					font-family: "Source Sans Pro", "Open Sans", sans-serif !important;
					background-color: <?php echo esc_html( silicon_adjust_color_brightness( $background_color, -7 ) ); ?> !important;
				}

				.wp-embed .wp-embed-featured-image {
					margin-bottom: 2.618em;
				}

				.wp-embed .wp-embed-featured-image img,
				.wp-embed .wp-embed-featured-image.square {
					min-width: 100%;
					margin-bottom: .618em;
				}

				a.wc-embed-button {
					padding: .857em 1.387em !important;
					font-weight: 600;
					background-color: <?php echo esc_attr( $accent_color ); ?>;
					color: #fff !important;
					border: 0 !important;
					line-height: 1;
					border-radius: 0 !important;
					box-shadow:
						inset 0 -1px 0 rgba(#000,.3);
				}

				a.wc-embed-button + a.wc-embed-button {
					background-color: #60646c;
				}
			</style>
			<?php
		}

		/**
		 * Override comment form fields
		 *
		 * @param array $fields The comment form fields.
		 * @return array
		 */
		public function comment_form_fields( $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( 'cookies' === $key ) {
					$label_class = 'form-check-label';
					$input_class = 'form-check-input';
				} else {
					$label_class = 'form-label fs-base';
					$input_class = 'form-control form-control-lg';
				}

				$field = str_replace( '<input', '<input class="' . esc_attr( $input_class ) . '"', $field );
				$field = str_replace( '<label', '<label class="' . esc_attr( $label_class ) . '"', $field );

				if ( 'author' === $key || 'email' === $key ) {
					$field = '<div class="col-sm-6 col-12">' . $field . '</div>';
				}

				if ( 'cookies' === $key ) {
					$field = str_replace( 'class="comment-form-cookies-consent"', 'class="form-check comment-form-cookies-consent"', $field );
					$field = '<div class="col-12">' . $field . '</div>';
				}

				$fields[ $key ] = $field;
			}
			return $fields;
		}

		/**
		 * Output comment form after HTML.
		 */
		public function output_comment_after() {
			?>
			</div></div></div>
			<?php
			// The opening is part of 'title_reply_before' index of the comment args.
		}

		/**
		 * Determine if the header should have shadow or not.
		 *
		 * @param bool $has_shadow The existing option.
		 * @return bool
		 */
		public function has_shadow( $has_shadow ) {
			if ( is_single() ) {
				$has_shadow = false;
			}
			return $has_shadow;
		}

		/**
		 * Built Elementor content for mas_static_content shortcode.
		 *
		 * @param string       $output Shortcode output.
		 * @param string       $tag    Shortcode name.
		 * @param array|string $attr   Shortcode attributes array or empty string.
		 */
		public function build_elementor_content( $output, $tag, $attr ) {
			if ( 'mas_static_content' === $tag && did_action( 'elementor/loaded' ) ) {
				$output = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $attr['id'] );
			}
			return $output;
		}

		/**
		 * Style the password form.
		 *
		 * @param string  $output The password form HTML output.
		 * @param WP_Post $post   Post object.
		 */
		public function style_password_form( $output, $post ) {
			$label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID ); //phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
			$output = '<p>' . esc_html__( 'This content is password protected. To view it please enter your password below:', 'silicon' ) . '</p><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post" style="max-width:500px;"><div class="input-group"><label class="visually-hidden" for="' . $label . '">' . esc_html__( 'Password:', 'silicon' ) . '</label></div><div class="input-group"><input name="post_password" id="' . $label . '" type="password" placeholder="' . esc_html__( 'Enter Password', 'silicon' ) . '" class="form-control" size="20"><input class="btn btn-primary" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form', 'silicon' ) . '" /></div></form>
    		';
			return $output;
		}

		/**
		 * Set the site mode by adding a class attribute to HTML element.
		 *
		 * @param string $output A space-separated list of language attributes.
		 * @param string $doctype The type of HTML document (xhtml|html).
		 * @return string
		 */
		public function set_site_mode( $output, $doctype ) {
			if ( apply_filters( 'silicon_is_dark_mode', false ) ) {
				$output .= ' class="dark-mode"';
			}

			return $output;
		}

		/**
		 * Add inline scripts.
		 */
		public function inline_scripts() {
			global $silicon_version;
			wp_register_script( 'silicon-inline-js', '', array(), $silicon_version );
			wp_enqueue_script( 'silicon-inline-js' );
			$this->page_loader_inline_js();
			$this->mode_switcher_inline_js();
		}

		/**
		 * Page Loader inline script.
		 */
		public function page_loader_inline_js() {
			if ( silicon_is_page_loader_enabled() ) {
				$script = 'window.onload=function(){const e=document.querySelector(".page-loading");e.classList.remove("active"),setTimeout(function(){e.remove()},1e3)};';
				wp_add_inline_script( 'silicon-inline-js', $script );
			}
		}

		/**
		 * Mode switcher inline script.
		 */
		public function mode_switcher_inline_js() {
			if ( silicon_enable_mode_switcher() ) {
				$script = 'let mode=window.localStorage.getItem("mode"),root=document.getElementsByTagName("html")[0];root.classList.contains("dark-mode")&&null==mode&&(mode="dark");void 0!==mode&&"dark"===mode?root.classList.add("dark-mode"):root.classList.remove("dark-mode");';
				wp_add_inline_script( 'silicon-inline-js', $script );
			}
		}
	}
endif;

return new Silicon();
