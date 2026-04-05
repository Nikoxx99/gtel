<?php
/**
 * Hooks when using ACF.
 */

add_filter( 'silicon_cover_image_url', 'silicon_acf_cover_image_url', 10 );
add_filter( 'silicon_get_category_list', 'silicon_acf_main_category', 10 );
add_filter( 'silicon_cat_bg', 'silicon_acf_main_cat_bg', 10 );
add_filter( 'silicon_post_duration_text', 'silicon_acf_single_podcast_duration', 10 );
