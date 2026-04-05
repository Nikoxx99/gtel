<?php
namespace SiliconElementor\Includes\Controls;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Base_Data_Control;

class Control_Font_Size extends Base_Data_Control {

    public function get_type() {
        return 'font_size';
    }

    public static function get_sizes() {
        
        $font_sizes = [
            ''                  => esc_html__( 'Normal', 'silicon-elementor' ),
            'display-1'         => esc_html__( 'Display 1', 'silicon-elementor' ),
            'display-2'         => esc_html__( 'Display 2', 'silicon-elementor' ),
            'display-3'         => esc_html__( 'Display 3', 'silicon-elementor' ),
            'display-4'         => esc_html__( 'Display 4', 'silicon-elementor' ),
            'display-5'         => esc_html__( 'Display 5', 'silicon-elementor' ),
            'display-6'         => esc_html__( 'Display 6', 'silicon-elementor' ),
            'fs-1'              => esc_html__( 'Font Size 1', 'silicon-elementor' ),
            'fs-2'              => esc_html__( 'Font Size 2', 'silicon-elementor' ),
            'fs-3'              => esc_html__( 'Font Size 3', 'silicon-elementor' ),
            'fs-4'              => esc_html__( 'Font Size 4', 'silicon-elementor' ),
            'fs-5'              => esc_html__( 'Font Size 5', 'silicon-elementor' ),
            'fs-6'              => esc_html__( 'Font Size 6', 'silicon-elementor' ),
            'lead'              => esc_html__( 'Lead', 'silicon-elementor' ),
            'fs-lg'             => esc_html__( 'Font Size lg', 'silicon-elementor' ),
            'fs-sm'             => esc_html__( 'Font Size sm', 'silicon-elementor' ),
            'fs-xs'             => esc_html__( 'Font Size sm', 'silicon-elementor' ),
            'h1'                => esc_html__( 'H1', 'silicon-elementor' ),
            'h2'                => esc_html__( 'H2', 'silicon-elementor' ),
            'h3'                => esc_html__( 'H3', 'silicon-elementor' ),
            'h4'                => esc_html__( 'H4', 'silicon-elementor' ),
            'h5'                => esc_html__( 'H5', 'silicon-elementor' ),
            'h6'                => esc_html__( 'H6', 'silicon-elementor' ),

        ];

        $additional_sizes = apply_filters( 'silicon-elementor/controls/jh-font-size/font_size_options', [] );

        return array_merge( $font_sizes, $additional_sizes );
    }

    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo $control_uid; ?>" data-setting="{{ data.name }}">
                    <?php foreach ( static::get_sizes() as $key => $size ) : ?>
                    <option value="<?php echo $key; ?>"><?php echo $size; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}