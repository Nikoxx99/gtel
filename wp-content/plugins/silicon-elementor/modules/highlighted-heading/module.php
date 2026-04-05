<?php
namespace SiliconElementor\Modules\HighlightedHeading;

use SiliconElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Module extends Module_Base {

    public function get_widgets() {
        return [
            'Highlighted_Heading',
        ];
    }

    public function get_name() {
        return 'highlighted-heading';
    }
}