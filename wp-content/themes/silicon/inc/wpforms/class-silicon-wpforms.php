<?php
/**
 * Silicon WPForms Integration
 *
 * @package Silicon/WPForms
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Silicon_WPForms' ) ) :
	/**
	 * Silicon WPForms Integration class
	 */
	class Silicon_WPForms {
		/**
		 * Instantiate the class with hooks.
		 */
		public function __construct() {
			add_filter( 'wpforms_field_properties', array( $this, 'field_atts' ), 10, 3 );
			add_filter( 'wpforms_frontend_form_atts', array( $this, 'frontend_form_atts' ), 10, 2 );
			add_action( 'wpforms_form_settings_general', array( $this, 'settings_general' ), 10, 1 );
			add_action( 'init', array( $this, 'move_footer_inside_fields_container' ), 30 );
			add_filter( 'wpforms_field_properties_checkbox', array( $this, 'field_properties_checkbox' ), 10, 3 );
			add_filter( 'wpforms_field_properties_email', array( $this, 'field_properties_email' ), 10, 3 );
			add_action( 'wpforms_display_field_email', array( $this, 'print_email_icon' ), 10, 3 );
			add_action( 'wpforms_display_field_email', array( $this, 'print_email_icon_close' ), 20, 3 );
			add_action( 'wpforms_display_field_before', array( $this, 'print_checkbox_wrap' ), 10, 2 );
			add_action( 'wpforms_display_field_after', array( $this, 'print_checkbox_wrap_close' ), 10, 2 );
			add_filter( 'wpforms_display_submit_before', array( $this, 'wrap_button_start' ) );
			add_filter( 'wpforms_display_submit_after', array( $this, 'wrap_button_end' ) );
		}

		/**
		 * Wrap button if footer class has input-group.
		 *
		 * @param array $form_data  Form data and settings.
		 */
		public function wrap_button_start( $form_data ) {
			if ( ! empty( $form_data['settings']['form_footer_class'] ) && false !== strpos( $form_data['settings']['form_footer_class'], 'has-input-group' ) ) :
				$class_wrap = '';
				preg_match_all( '/has-([^\s]+)/', $form_data['settings']['form_footer_class'], $matches );
				if ( isset( $matches[1] ) ) {
					$class_wrap .= ' ' . implode( ' ', $matches[1] );
				}

				$class_btn = 'btn';
				preg_match_all( '/submit-([^\s]+)/', $form_data['settings']['form_footer_class'], $matches );
				if ( isset( $matches[1] ) ) {
					$class_btn .= ' ' . implode( ' ', $matches[1] );
				}

				echo '<div class="' . esc_attr( trim( $class_wrap ) ) . '"><div></div><div class="' . esc_attr( trim( $class_btn ) ) . '">';
			endif;
		}

		/**
		 * Wrap button if footer class has input-group.
		 *
		 * @param array $form_data  Form data and settings.
		 */
		public function wrap_button_end( $form_data ) {
			if ( ! is_admin() ) {
				if ( ! empty( $form_data['settings']['form_footer_class'] ) && false !== strpos( $form_data['settings']['form_footer_class'], 'has-input-group' ) ) :
					echo '</div></div>';
				endif;
			}
		}

		/**
		 * Print Email Icon start.
		 *
		 * @param array $field      Field data and settings.
		 * @param array $attributes Field attributes.
		 * @param array $form_data  Form data and settings.
		 */
		public function print_email_icon( $field, $attributes, $form_data ) {
			if ( false !== strpos( $field['css'], 'si-email-icon' ) ) {
				$class_wrap = 'position-relative';
				preg_match_all( '/ig-([^\s]+)/', $field['css'], $matches );
				if ( isset( $matches[1] ) ) {
					$class_wrap .= ' ' . implode( ' ', $matches[1] );
				}

				echo '<div class="' . esc_attr( $class_wrap ) . '">';
			}
		}

		/**
		 * Print Email Icon close.
		 *
		 * @param array $field      Field data and settings.
		 * @param array $attributes Field attributes.
		 * @param array $form_data  Form data and settings.
		 */
		public function print_email_icon_close( $field, $attributes, $form_data ) {
			if ( false !== strpos( $field['css'], 'si-email-icon' ) ) {
				$class_icon = 'bx bx-envelope position-absolute start-0 top-50 translate-middle-y ms-3 zindex-5 text-muted';

				preg_match_all( '/ic-([^\s]+)/', $field['css'], $matches );
				if ( isset( $matches[1] ) ) {
					$class_icon .= ' ' . implode( ' ', $matches[1] );
				}
				echo '<i class="' . esc_attr( $class_icon ) . '"></i></div>';
			}
		}

		/**
		 * Print Email Icon start.
		 *
		 * @param array $field      Field data and settings.
		 * @param array $form_data  Form data and settings.
		 */
		public function print_checkbox_wrap( $field, $form_data ) {
			if ( false !== strpos( $field['css'], 'si-checkbox-inline' ) ) {
				echo '<div class="row align-items-center">';
			}
		}

		/**
		 * Print Email Icon close.
		 *
		 * @param array $field      Field data and settings.
		 * @param array $form_data  Form data and settings.
		 */
		public function print_checkbox_wrap_close( $field, $form_data ) {
			if ( false !== strpos( $field['css'], 'si-checkbox-inline' ) ) {
				echo '</div>';
			}
		}

		/**
		 * Override Email Properties.
		 *
		 * @param array $properties Field properties.
		 * @param array $field      Field settings.
		 * @param array $form_data  Form data and settings.
		 *
		 * @return array
		 */
		public function field_properties_email( $properties, $field, $form_data ) {
			if ( false !== strpos( $field['css'], 'si-email-icon' ) ) {
				$properties['inputs']['primary']['class'][] = 'ps-5';
			}
			return $properties;
		}

		/**
		 * Override Checkbox Properties.
		 *
		 * @param array $properties Field properties.
		 * @param array $field      Field settings.
		 * @param array $form_data  Form data and settings.
		 *
		 * @return array
		 */
		public function field_properties_checkbox( $properties, $field, $form_data ) {

			// Styling for si-checkbox field class.
			if ( false !== strpos( $field['css'], 'si-checkbox' ) ) {
				$choices = $field['choices'];

				$properties['input_container']['class'][] = 'mt-n2';
				foreach ( $choices as $key => $choice ) {
					$properties['inputs'][ $key ]['container']['class'][] = 'form-check';
					$properties['inputs'][ $key ]['container']['class'][] = 'mb-0';
					$properties['inputs'][ $key ]['container']['class'][] = 'mt-2';
					$properties['inputs'][ $key ]['label']['class'][]     = 'form-check-label';
					$properties['inputs'][ $key ]['class'][]              = 'form-check-input';
					$properties['inputs'][ $key ]['class'][]              = 'si-form-check-input';
					$properties['inputs'][ $key ]['class'][]              = 'me-0';
					$properties['inputs'][ $key ]['class'][]              = 'ms-n4';
				}
			}

			// Set the label class for Heading.
			if ( false !== strpos( $field['css'], 'si-checkbox-label-heading' ) ) {
				$properties['label']['class'][] = 'h5';
				$properties['label']['class'][] = 'mb-0';
				$properties['label']['class'][] = 'text-sm-start';
				$properties['label']['class'][] = 'text-center';
				$properties['label']['class'][] = 'fw-bold';
			}

			// Set the inline class.
			if ( false !== strpos( $field['css'], 'si-checkbox-inline' ) ) {
				$properties['label']['class'][]           = 'col-md-3';
				$properties['label']['class'][]           = 'me-4';
				$properties['input_container']['class'][] = 'col';
				$properties['input_container']['class'][] = 'row-margin';
			}

			return $properties;
		}

		/**
		 * Move Footer inside fields container.
		 */
		public function move_footer_inside_fields_container() {
			$wpforms_fronend = wpforms()->frontend;
			$wpforms_captcha = wpforms()->captcha;
			remove_action( 'wpforms_frontend_output', [ $wpforms_fronend, 'fields' ], 10, 5 );
			add_action( 'wpforms_frontend_output', [ $this, 'frontend_fields' ], 10, 5 );
			remove_action( 'wpforms_frontend_output', [ $wpforms_captcha, 'recaptcha' ], 20, 5 );
			remove_action( 'wpforms_frontend_output', [ $wpforms_fronend, 'foot' ], 25, 5 );
			add_action( 'silicon_wpforms_form_footer', [ $wpforms_captcha, 'recaptcha' ], 20, 5 );
			add_action( 'silicon_wpforms_form_footer', [ $wpforms_fronend, 'foot' ], 20, 5 );
		}

		/**
		 * Form field area.
		 *
		 * @param array $form_data   Form data and settings.
		 * @param null  $deprecated  Deprecated in v1.3.7, previously was $form object.
		 * @param bool  $title       Whether to display form title.
		 * @param bool  $description Whether to display form description.
		 * @param array $errors      List of all errors filled in WPForms_Process::process().
		 */
		public function frontend_fields( $form_data, $deprecated, $title, $description, $errors ) {
			// Obviously we need to have form fields to proceed.
			if ( empty( $form_data['fields'] ) ) {
				return;
			}

			$class_field_container = 'wpforms-field-container';
			if ( ! empty( $form_data['settings']['fields_wrapper_class'] ) ) {
				$class_field_container .= ' ' . $form_data['settings']['fields_wrapper_class'];
			}

			$class_form_footer = 'wpforms-footer wpf-sc-p-0';
			if ( ! empty( $form_data['settings']['form_footer_class'] ) ) {
				$class_form_footer .= ' ' . $form_data['settings']['form_footer_class'];
			}

			if ( ! empty( $form_data['settings']['form_footer_class'] ) && false !== strpos( $form_data['settings']['form_footer_class'], 'has-input-group' ) ) {
				$form_data['settings']['submit_class'] .= ' bg-transparent border-0 text-reset fw-medium p-0';
			}

			$wpforms_fronend = wpforms()->frontend;

			// Form fields area.
			echo '<div class="' . esc_attr( trim( $class_field_container ) ) . '">';

				/**
				 * Core actions on this hook:
				 * Priority / Description
				 * 20         Pagebreak markup (open first page)
				 */
				do_action( 'wpforms_display_fields_before', $form_data );

				// Loop through all the fields we have.
			foreach ( $form_data['fields'] as $field ) :
				if ( ! has_action( "wpforms_display_field_{$field['type']}" ) ) {
					continue;
				}

				/**
				 *
				 * Modify Field before render.
				 *
				 * @since 1.4.0
				 *
				 * @param array $field Current field.
				 * @param array $form_data Form data and settings.
				 */
				$field = apply_filters( 'wpforms_field_data', $field, $form_data );

				if ( empty( $field ) ) {
					continue;
				}

				// Get field attributes. Deprecated; Customizations should use
				// field properties instead.
				$attributes = $wpforms_fronend->get_field_attributes( $field, $form_data );

				// Add properties to the field so it's available everywhere.
				$field['properties'] = $wpforms_fronend->get_field_properties( $field, $form_data, $attributes );

				/**
				 * Core actions on this hook:
				 * Priority / Description
				 * 5          Field opening container markup.
				 * 15         Field label.
				 * 20         Field description (depending on position).
				 */
				do_action( 'wpforms_display_field_before', $field, $form_data );

				/**
				 * Individual field classes use this hook to display the actual
				 * field form elements.
				 * See `field_display` methods in /includes/fields.
				 */
				do_action( "wpforms_display_field_{$field['type']}", $field, $attributes, $form_data );

				/**
				 * Core actions on this hook:
				 * Priority / Description
				 * 3          Field error messages.
				 * 5          Field description (depending on position).
				 * 15         Field closing container markup.
				 * 20         Pagebreak markup (close previous page, open next)
				 */
				do_action( 'wpforms_display_field_after', $field, $form_data );

				endforeach;

				/**
				 * Core actions on this hook:
				 * Priority / Description
				 * 5          Pagebreak markup (close last page)
				 */
				do_action( 'wpforms_display_fields_after', $form_data );

				echo '<div class="' . esc_attr( $class_form_footer ) . '">';

					do_action( 'silicon_wpforms_form_footer', $form_data, $deprecated, $title, $description, $errors );

				echo '</div>';

			echo '</div>';
		}

		/**
		 * Adding WPForms field attributes
		 *
		 * @param array $properties Field properties..
		 * @param array $field Form Fields.
		 * @param array $form_data Form data.
		 * @return array $properties Form properties.
		 */
		public function field_atts( $properties, $field, $form_data ) {
			$form_control_class = array();
			if ( ! empty( $form_data['settings']['form_control_class'] ) ) {
				$form_control_class = explode( ' ', $form_data['settings']['form_control_class'] );
			}
			$form_control_class[] = 'form-control';

			if ( isset( $properties['inputs'] ) && isset( $properties['inputs']['primary'] ) && isset( $properties['inputs']['primary']['class'] ) ) {
				$properties['inputs']['primary']['class'] = array_merge( $properties['inputs']['primary']['class'], $form_control_class );
			}

			if ( isset( $properties['inputs']['first']['class'] ) ) {
				$properties['inputs']['first']['class'] = array_merge( $properties['inputs']['first']['class'], $form_control_class );
			}

			if ( isset( $properties['inputs']['middle']['class'] ) ) {
				$properties['inputs']['middle']['class'] = array_merge( $properties['inputs']['middle']['class'], $form_control_class );
			}

			if ( isset( $properties['inputs']['last']['class'] ) ) {
				$properties['inputs']['last']['class'] = array_merge( $properties['inputs']['last']['class'], $form_control_class );
			}

			if ( isset( $properties['label'] ) && isset( $properties['label']['class'] ) ) {
				if ( ! in_array( 'si-checkbox-label-heading', $properties['container']['class'], true ) ) {
					$properties['label']['class'][] = 'form-label';
					$properties['label']['class'][] = 'fw-medium';
				}
			}

			if ( 'textarea' === $field['type'] ) {
				$properties['inputs']['primary']['attr']['rows'] = '4';
				$properties['inputs']['primary']['class'][] = 'h-auto';
			}

			if ( 'select' === $field['type'] ) {
				$properties['input_container']['class'][] = 'form-select';
			}

			if ( in_array( 'form-control-lg', $form_control_class, true ) ) {
				if ( 'select' === $field['type'] ) {
					$properties['label']['class'][]           = 'fs-base';
					$properties['input_container']['class'][] = 'form-select-lg';
				}

				if ( 'checkbox' === $field['type'] && false === strpos( $field['css'], 'si-checkbox-label-heading' ) ) {
					foreach ( $properties['inputs'] as $key => $input ) {
						$properties['label']['class'][]                   = 'fs-base';
						$properties['inputs'][ $key ]['label']['class'][] = 'fs-base';
					}
				}
			}

			return $properties;
		}

		/**
		 * Begin to build the output
		 *
		 * @param array $form_atts Form atts.
		 * @param array $form_data Form data.
		 */
		public function frontend_form_atts( $form_atts, $form_data ) {

			if ( isset( $form_data['settings']['form_tag_class'] ) && ! empty( $form_data['settings']['form_tag_class'] ) ) {
				$form_classes       = explode( ' ', $form_data['settings']['form_tag_class'] );
				$form_atts['class'] = array_merge( $form_atts['class'], $form_classes );
			}

			return $form_atts;
		}

		/**
		 * Adding WPForm field
		 *
		 * @param object $settings Adding Form settings.
		 */
		public function settings_general( $settings ) {
			wpforms_panel_field(
				'text',
				'settings',
				'form_tag_class',
				$settings->form_data,
				esc_html__( 'Form Tag Class', 'silicon' ),
				[
					'tooltip' => esc_html__( 'Enter CSS class names for the form tag. Multiple class names should be separated with spaces.', 'silicon' ),
				]
			);
			wpforms_panel_field(
				'text',
				'settings',
				'fields_wrapper_class',
				$settings->form_data,
				esc_html__( 'Fields Wrapper Class', 'silicon' ),
				[
					'tooltip' => esc_html__( 'Enter CSS class names for the fields wrapper. Multiple class names should be separated with spaces.', 'silicon' ),
				]
			);
			wpforms_panel_field(
				'text',
				'settings',
				'form_control_class',
				$settings->form_data,
				esc_html__( 'Form Control CSS', 'silicon' ),
				[
					'tooltip' => esc_html__( 'Enter additional CSS class names for the form_control element. Multiple class names should be separated with spaces.', 'silicon' ),
				]
			);
			wpforms_panel_field(
				'text',
				'settings',
				'form_footer_class',
				$settings->form_data,
				esc_html__( 'Form Footer CSS', 'silicon' ),
				[
					'tooltip' => esc_html__( 'Enter additional CSS class names for the form footer element. Multiple class names should be separated with spaces.', 'silicon' ),
				]
			);
		}
	}
endif;

return new Silicon_WPForms();
