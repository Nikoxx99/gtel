<?php
	acf_add_local_field_group(
		array(
			'key'                   => 'group_621748bc4f913',
			'title'                 => 'Silicon - Footer Options',
			'fields'                => array(
				array(
					'key'               => 'field_62176065fa1df',
					'label'             => 'Custom Footer',
					'name'              => 'silicon_enable_footer',
					'type'              => 'true_false',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'message'           => '',
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => '',
					'ui_off_text'       => '',
				),
				array(
					'key'               => 'field_621748c33c241',
					'label'             => 'Footer Variant',
					'name'              => 'silicon_footer_variant',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_62176065fa1df',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'none'           => 'Default',
						'static-content' => 'Static Footer',
						'no-footer'      => 'None',
					),
					'default_value'     => false,
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
				),
				array(
					'key'               => 'field_62175efe8e1f4',
					'label'             => 'Static Footers',
					'name'              => 'silicon_static_footers',
					'type'              => 'relationship',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_621748c33c241',
								'operator' => '==',
								'value'    => 'static-content',
							),
							array(
								'field'    => 'field_62176065fa1df',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'post_type'         => array(
						0 => 'mas_static_content',
					),
					'taxonomy'          => '',
					'filters'           => array(
						0 => 'search',
						1 => 'post_type',
						2 => 'taxonomy',
					),
					'elements'          => '',
					'min'               => 1,
					'max'               => 1,
					'return_format'     => 'id',
				),
				array(
					'key' => 'field_6221a4caa72d3',
					'label' => 'Copyright Text',
					'name' => 'silicon_copyright_text',
					'type' => 'textarea',
					'instructions' => 'Only in Default footer and 404 v1',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_621748c33c241',
								'operator' => '!=',
								'value'    => 'static-content',
							),
							array(
								'field'    => 'field_62176065fa1df',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '\'© All rights reserved. Made with <i class="bx bx-heart d-inline-block fs-lg text-gradient-primary align-middle mt-n1 mx-1"></i> by&nbsp; <a href="https://madrasthemes.com/" class="text-muted" target="_blank" rel="noopener">MadrasThemes</a>',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'new_lines' => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'jetpack-portfolio',
					),
				),
			),
			'menu_order'            => 10,
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

