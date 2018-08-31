<?php
/**
 * ACF integration.
 * Props Jaime Martínez / Level Level for their contributions.
 *
 * @link https://github.com/level-level/wp-cfm-acf
 *
 * @package woocart-defaults
 */
class WCD_Advanced_Custom_Fields {

    /**
     * Class Constructor.
     *
     * @access public
     * @since  1.0.0 
     */
	public function __construct() {
		add_filter( 'wcd_configuration_items', array( &$this, 'configuration_items' ) );
	}

	/**
	 * Register the field groups in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		$field_groups = (array) $this->get_field_groups();

		foreach ( $field_groups as $group ) {
			$items[ 'acf/' . $group['post_name'] ] = array(
				'value'    => json_encode( $group ),
				'label'    => $group['post_title'],
				'group'    => 'Advanced Custom Fields',
				'callback' => array( $this, 'acf_pull' ),
			);
		}

		return $items;
	}

	/**
	 * Export an ACF field group.
     *
     * @access public
	 */
	public function acf_push( $group_id ) {
		// Load field group.
		$field_group = acf_get_field_group( $group_id );

		// Validate field group.
		if ( empty( $field_group ) ) {
			return;
		}

		// Load fields.
		$fields = acf_get_fields( $field_group );

		// Prepare fields.
		$fields = acf_prepare_fields_for_export( $fields );

		// Add to field group.
		$field_group['fields'] = $fields;

		// Extract field group ID.
		$id = acf_extract_var( $field_group, 'ID' );

		// Add to json array.
		return $field_group;
	}

	/**
	 * Import (overwrite) field groups into DB
	 *
	 * @param string $params['name']
	 * @param string $params['group']
	 * @param string $params['old_value'] The previous settings data
	 * @param string $params['new_value'] The new settings data
     *
     * @access public
	 */
	public function acf_pull( $params ) {
		$field_group = $params['new_value'];

		if ( $existing_group = acf_get_field_group( $field_group['key'] ) ) {
			$field_group['ID'] = $existing_group['ID'];
			$existing_fields   = acf_get_fields( $existing_group );

			// Remove fields.
			foreach ( $existing_fields as $field ) {
				wp_delete_post( $field['ID'], true );
			}
		}

		// Extract fields.
		$fields = acf_extract_var( $field_group, 'fields' );

		// Format fields.
		$fields = acf_prepare_fields_for_import( $fields );

		// Save field group.
		$field_group = acf_update_field_group( $field_group );

		// Add to ref.
		$ref[ $field_group['key'] ] = $field_group['ID'];

		// Add to order.
		$order[ $field_group['ID'] ] = 0;

		// Add fields.
		foreach ( $fields as $index => $field ) {
			// Add parent.
			if ( empty( $field['parent'] ) ) {
				$field['parent'] = $field_group['ID'];
			} elseif ( isset( $ref[ $field['parent'] ] ) ) {
				$field['parent'] = $ref[ $field['parent'] ];
			}

			// Add field menu_order.
			if ( ! isset( $order[ $field['parent'] ] ) ) {
				$order[ $field['parent'] ] = 0;
			}

			$field['menu_order'] = $order[ $field['parent'] ];
			$order[ $field['parent'] ]++;

			// Save field.
			$field                   = acf_update_field( $field );

			// Sdd to ref.
			$ref[ $field['key'] ]    = $field['ID'];
		}
	}

	/**
	 * Return an array of ACF field groups.
     *
     * @access public
	 */
	public function get_field_groups() {
		global $wpdb;

		$sql = "SELECT ID, post_name, post_title FROM {$wpdb->posts} WHERE post_type = 'acf' AND post_status = 'publish'";
		return $wpdb->get_results( $sql, ARRAY_A );
	}

}
