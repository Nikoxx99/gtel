<?php
/**
 * Template Library Filter Item
 */

// phpcs:ignoreFile
?>
<label class="si-premium-template-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="si-premium-template-filter">
	<span>{{ title.replace('&amp;', '&') }}</span>
</label>
