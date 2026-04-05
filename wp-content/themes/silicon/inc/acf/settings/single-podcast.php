<?php

acf_add_local_field_group(
	array(
		'key'                   => 'group_62136164e2fab',
		'title'                 => 'Silicon - Podcast Options',
		'fields'                => array(
			array(
				'key'               => 'field_6213617ba5be7',
				'label'             => 'Upload Audio',
				'name'              => 'upload_audio',
				'type'              => 'file',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'return_format'     => 'url',
				'library'           => 'all',
				'min_size'          => '',
				'max_size'          => '',
				'mime_types'        => '',
			),
			array(
				'key'               => 'field_6213627374138',
				'label'             => 'Duration',
				'name'              => 'duration',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
				'placeholder'       => '00:00:00',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
			),
			array(
				'key'               => 'field_621361b4a5be8',
				'label'             => 'Timeline',
				'name'              => 'timeline',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
				'placeholder'       => '00:00:00| timeline text',
				'maxlength'         => '',
				'rows'              => '',
				'new_lines'         => '',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_format',
					'operator' => '==',
					'value'    => 'audio',
				),
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
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


