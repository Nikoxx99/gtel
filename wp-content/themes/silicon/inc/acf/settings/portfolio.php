<?php
/**
 * Acf Settings
 *
 * @package silicon
 */

acf_add_local_field_group(
	array(
		'key'                   => 'group_620b89be7a272',
		'title'                 => 'Silicon - Portfolio Options',
		'fields'                => array(
			array(
				'key'               => 'field_620b89e1f5cca',
				'label'             => 'Project Details',
				'name'              => 'portfolio_slider_list',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => 'Client|Createx Inc.',
				'placeholder'       => 'Heading|Data',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => '',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'jetpack-portfolio',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
		'show_in_rest'          => 0,
	)
);
