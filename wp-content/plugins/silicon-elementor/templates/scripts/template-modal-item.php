<?php
/**
 * Template Item
 */

// phpcs:ignoreFile
?>

<div class="elementor-template-library-template-body">
	<div class="elementor-template-library-template-screenshot">
		<div class="elementor-template-library-template-preview">
			<i class="fa fa-search-plus"></i>
		</div>
		<img src="{{ thumbnail }}" alt="{{ title }}">
		{{ title }}
	</div>
</div>
<div class="elementor-template-library-template-controls">
	<# if ( 'valid' === window.SiPremiumTempsData.license.status || ! pro ) { #>
		<button class="elementor-template-library-template-action si-premium-template-insert elementor-button elementor-button-success">
			<i class="eicon-file-download"></i>
				<span class="elementor-button-title"><?php echo __( 'Insert', 'silicon-elementor' ); ?></span>
		</button>
	<# } else if ( pro ) { #>
	<a class="template-library-activate-license" href="{{{ window.SiPremiumTempsData.license.activateLink }}}" target="_blank">
		<i class="fa fa-external-link" aria-hidden="true"></i>
		{{{ window.SiPremiumTempsData.license.proMessage }}}
	</a>    
	<# } #>
</div>

<!--<div class="elementor-template-library-template-name">{{{ title }}}</div>-->
