<?php
/**
 * WooCommerce shipping integration.
 *
 * @package woocart-defaults
 */
class WOO_Shipping {


	/**
	 * Class Constructor.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct() {
		add_filter( 'wcd_configuration_items', array( &$this, 'configuration_items' ) );
		add_filter( 'wcd_pull_callback', array( &$this, 'pull_callback' ), 10, 2 );
	}

	/**
	 * Register the shipping zones in WCD.
	 *
	 * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query = "SELECT * FROM {$wpdb->prefix}woocommerce_shipping_zones";
		$zones = $wpdb->get_results( $query );

		foreach ( $zones as $zone ) {
			$locations = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT location_code, location_type FROM {$wpdb->prefix}woocommerce_shipping_zone_locations WHERE zone_id = %d",
					$zone->zone_id
				),
				'ARRAY_A'
			);

			$methods = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT method_id, method_order, is_enabled FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE zone_id = %d",
					$zone->zone_id
				),
				'ARRAY_A'
			);

			$value = array(
				'name'      => $zone->zone_name,
				'order'     => $zone->zone_order,
				'locations' => $locations,
				'methods'   => $methods,
			);

			$items[ 'wooship/' . $zone->zone_id ] = array(
				'value' => json_encode( $value ),
				'label' => $zone->zone_name,
				'group' => 'WooCommerce Shipping Zones',
			);

		}

		return $items;
	}

	/**
	 * Pull callback.
	 *
	 * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'wooship/' == substr( $callback_params['name'], 0, 8 ) ) {
			return array( &$this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) shipping zones into the DB
	 *
	 * @param string $params['name']
	 * @param string $params['group']
	 * @param string $params['old_value'] The old settings (DB)
	 * @param string $params['new_value'] The new settings (file)
	 *
	 * @access public
	 */
	public function import_terms( $params ) {
		global $wpdb;

		$id   = intval( str_replace( 'wooship/', '', $params['name'] ) );
		$data = $params['new_value'];
		$wpdb->replace(
			"{$wpdb->prefix}woocommerce_shipping_zones",
			array(
				'zone_id'    => $id,
				'zone_name'  => $data['name'],
				'zone_order' => $data['order'],
			),
			array(
				'%d',
				'%s',
				'%d',
			)
		);

		foreach ( $data['locations'] as $location ) {
			$wpdb->replace(
				"{$wpdb->prefix}woocommerce_shipping_zone_locations",
				array(
					'zone_id'       => $id,
					'location_code' => $location['location_code'],
					'location_type' => $location['location_type'],
				),
				array(
					'%d',
					'%s',
					'%s',
				)
			);
		}

		foreach ( $data['methods'] as $method ) {
			$wpdb->replace(
				"{$wpdb->prefix}woocommerce_shipping_zone_methods",
				array(
					'zone_id'      => $id,
					'method_id'    => $method['method_id'],
					'method_order' => $method['method_order'],
					'is_enabled'   => $method['is_enabled'],
				),
				array(
					'%d',
					'%s',
					'%d',
					'%d',
				)
			);
		}
	}

}

if ( ! defined( 'WCD_TESTS' ) ) {
	new WOO_Shipping();
}
