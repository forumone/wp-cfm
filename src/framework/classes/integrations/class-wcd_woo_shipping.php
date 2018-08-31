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
	 * Register the taxonomies in WCD.
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
					"SELECT location_code, location_type FROM {$wpdb->prefix}woocommerce_shipping_zone_locations WHERE zone_id = %d", $zone->zone_id
				), 'ARRAY_A'
			);

			$methods = $wpdb->get_results(
				$wpdb->prepare( 
                    "SELECT method_id, method_order, is_enabled FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE zone_id = %d", $zone->zone_id
				), 'ARRAY_A'
			);

			$value = array(
				'name'      => $zone->zone_name,
				'order'     => $zone->zone_order,
				'locations' => $locations,
				'methods'   => $methods,
			);

			$items[ 'wooship/' . $zone->zone_id ] = array(
				'value' => json_encode( $value ),
				'label' => "$zone->zone_name",
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
		if ( 'wooship/' == substr( $callback_params['name'], 0, 4 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) taxonomies into the DB
	 *
	 * @param string $params['name']
	 * @param string $params['group']
	 * @param string $params['old_value'] The old settings (DB)
	 * @param string $params['new_value'] The new settings (file)
     *
     * @access public
	 */
	public function import_terms( $params ) {
		$wpdb->replace(
			"{$wpdb->prefix}woocommerce_tax_rates",
			array(
				'tax_rate_id'        => $id,
				'tax_rate_country'   => $data->country,
				'tax_rate_state'     => $data->state,
				'tax_rate_country'   => $data->country,
				'tax_rate'           => $data->rate,
				'tax_rate_name'      => $data->name,
				'tax_rate_priority'  => $data->priority,
				'tax_rate_compound'  => $data->compound,
				'tax_rate_shipping'  => $data->shipping,
				'tax_rate_orderrder' => $data->order,
				'tax_rate_class'     => $data->class,
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
	}

}

new WOO_Shipping();
