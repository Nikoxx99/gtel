<?php
/**
 * Silicon hooks
 *
 * @package silicon
 */

/**
 * General
 *
 * @see  silicon_page_loading_spinner()
 * @see  silicon_get_sidebar()
 */
add_action( 'silicon_before_site', 'silicon_page_loading_spinner', 10 );
add_action( 'silicon_sidebar', 'silicon_get_sidebar', 10 );
add_action( 'wp_head', 'silicon_pingback_header' );

/**
 * Header
 *
 * @see  silicon_skip_links()
 * @see  silicon_secondary_navigation()
 * @see  silicon_site_branding()
 * @see  silicon_navbar_nav()
 */
add_action( 'silicon_header', 'silicon_skip_links', 5 );
add_action( 'silicon_header', 'silicon_site_branding', 20 );
add_action( 'silicon_header', 'silicon_header_navbar_offcanvas', 30 );
add_action( 'silicon_header', 'silicon_mode_switcher', 40 );
add_action( 'silicon_header', 'silicon_navbar_toggler', 50 );
add_action( 'silicon_header', 'silicon_custom_button', 60 );
add_filter( 'silicon_is_dark_mode', 'silicon_default_mode', 10 );

/**
 * Footer
 *
 * @see  silicon_footer_widgets()
 * @see  silicon_credit()
 */
add_action( 'silicon_after_footer', 'silicon_back_to_top', 20 );

/**
 * Homepage
 *
 * @see  silicon_homepage_content()
 */
add_action( 'homepage', 'silicon_homepage_content', 10 );

/**
 * Posts
 *
 * @see  silicon_post_header()
 * @see  silicon_post_meta()
 * @see  silicon_post_content()
 * @see  silicon_paging_nav()
 * @see  silicon_single_post_header()
 * @see  silicon_post_nav()
 * @see  silicon_display_comments()
 * @see  silicon_single_podcast_header()
 * @see  silicon_single_podcast_timeline_content()
 * @see  silicon_single_podcast_comments()
 * @see  silicon_single_podcast_related_posts()
 */
add_action( 'silicon_loop_post', 'silicon_post_header', 10 );
add_action( 'silicon_loop_post', 'silicon_post_content', 30 );
add_action( 'silicon_loop_post', 'silicon_post_taxonomy', 40 );
add_action( 'silicon_loop_after', 'silicon_pagination', 10 );

add_action( 'silicon_single_post', 'silicon_single_post_header', 10 );
add_action( 'silicon_single_post', 'silicon_single_post_thumbnail', 20 );
add_action( 'silicon_single_post', 'silicon_single_post_content', 30 );
add_action( 'silicon_single_post', 'silicon_single_post_related_posts', 40 );

add_action( 'silicon_post_content_after', 'silicon_single_post_tags', 10 );
add_action( 'silicon_post_content_after', 'silicon_display_comments', 20 );

add_action( 'silicon_single_podcast', 'silicon_single_podcast_header', 10 );
add_action( 'silicon_single_podcast', 'silicon_single_podcast_timeline_content', 20 );
add_action( 'silicon_single_podcast_after', 'silicon_single_podcast_comments', 10 );
add_action( 'silicon_single_podcast_after', 'silicon_single_podcast_related_posts', 20 );

/**
 * Blog filter.
 * 
 * @see  grid_blog_search_form()
 * @see  grid_dropdown_category_filter()
 */
add_action( 'silicon_loop_before', 'grid_blog_search_form', 10 );
add_action( 'silicon_page_header', 'grid_dropdown_category_filter', 10 );

/**
 * Pages
 *
 * @see  silicon_page_header()
 * @see  silicon_page_content()
 * @see  silicon_display_comments()
 */
add_action( 'silicon_page_before', 'silicon_page_header', 10 );
add_action( 'silicon_page', 'silicon_page_content', 20 );
add_action( 'silicon_page', 'silicon_edit_post_link', 30 );
add_action( 'silicon_page', 'silicon_page_comments', 40 );
add_filter( 'silicon_page_loader_enabled', 'silicon_is_page_loader', 10 );

/**
 * Homepage Page Template
 *
 * @see  silicon_homepage_header()
 * @see  silicon_page_content()
 */
add_action( 'silicon_homepage', 'silicon_homepage_header', 10 );
add_action( 'silicon_homepage', 'silicon_page_content', 20 );

/**
 * Portfolio
 *
 * @see  silicon_portfolio_list_header()
 */
add_action( 'silicon_loop_portfolio_before', 'silicon_portfolio_section_header', 20 );
add_action( 'silicon_loop_portfolio_after', 'silicon_portfolio_slider_footer', 20 );
add_action( 'silicon_portfolio_slider_meta_before', 'silicon_portfolio_slider_list', 20 );
add_action( 'silicon_single_before_portfolio', 'silicon_single_project_header', 10 );
add_action( 'silicon_single_before_portfolio', 'silicon_single_project_thumbnail', 20 );

/**
 * Footer
 */
add_action( 'silicon_footer', 'silicon_footer_static', 10 );
add_filter( 'silicon_copyright_text', 'silicon_footer_copyright_text', 10 );
