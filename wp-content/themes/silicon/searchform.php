<?php
/**
 * Searchform template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<input type="search" class="form-control pe-5 rounded" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder', 'silicon' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'silicon' ); ?>">
		<i class="bx bx-search position-absolute top-50 end-0 translate-middle-y me-3 zindex-5 fs-lg"></i>
	</div>
	<input type="hidden" name="post_type" value="post">
</form>
