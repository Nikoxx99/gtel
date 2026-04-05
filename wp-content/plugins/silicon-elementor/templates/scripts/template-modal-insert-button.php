<?php
/**
 * Template Insert Button
 */

// phpcs:ignoreFile
?>
<# if ( 'valid' === window.SiPremiumTempsData.license.status || ! pro ) { #>
	<button class="elementor-template-library-template-action si-premium-template-insert elementor-button elementor-button-success">
		<i class="eicon-file-download"></i><span class="elementor-button-title">
		<?php
			echo __( 'Insert', 'silicon-elementor' );
		?>
		</span>
	</button>
<# } else { #>
<a class="template-library-activate-license elementor-button elementor-button-go-pro" href="{{{ window.SiPremiumTempsData.license.activateLink }}}" target="_blank">
	<i class="fa fa-external-link" aria-hidden="true"></i>
	{{{ window.SiPremiumTempsData.license.proMessage }}}
</a>
<# } #>
