<?php
/**
 * WooCommerce tax rates.
 *
 * @package woocart-defaults
 */
class WOO_Tax {


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
	 * Register the tax rates in WCD.
	 *
	 * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query     = "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates";
		$tax_rates = $wpdb->get_results( $query );

		foreach ( $tax_rates as $tax ) {
			$locations = $wpdb->get_results(
				$wpdb->prepare(
					"
				SELECT location_code, location_type
				FROM {$wpdb->prefix}woocommerce_tax_rate_locations
				WHERE tax_rate_id = %d
            ",
					$tax->tax_rate_id
				),
				'ARRAY_A'
			);

			$values = array(
				'country'   => $tax->tax_rate_country,
				'state'     => $tax->tax_rate_state,
				'rate'      => $tax->tax_rate,
				'name'      => $tax->tax_rate_name,
				'priority'  => $tax->tax_rate_priority,
				'compound'  => $tax->tax_rate_compound,
				'shipping'  => $tax->tax_rate_shipping,
				'order'     => $tax->tax_rate_order,
				'class'     => $tax->tax_rate_class,
				'locations' => $locations,
			);

			$items[ 'wootax/' . $tax->tax_rate_id ] = array(
				'value' => json_encode( $values ),
				'label' => "$tax->tax_rate_country - $tax->tax_rate_name - $tax->tax_rate $tax_rate_class",
				'group' => 'WooCommerce Tax Rates',
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
		if ( 'wootax/' == substr( $callback_params['name'], 0, 7 ) ) {
			return array( &$this, 'import_tax' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) tax rates into the DB
	 *
	 * @param string $params['name']
	 * @param string $params['group']
	 * @param string $params['old_value'] The old settings (DB)
	 * @param string $params['new_value'] The new settings (file)
	 *
	 * @access public
	 */
	public function import_tax( $params ) {
		global $wpdb;

		$id   = intval( str_replace( 'wootax/', '', $params['name'] ) );
		$data = $params['new_value'];
		$wpdb->replace(
			"{$wpdb->prefix}woocommerce_tax_rates",
			array(
				'tax_rate_id'        => $id,
				'tax_rate_country'   => $data['country'],
				'tax_rate_state'     => $data['state'],
				'tax_rate_country'   => $data['country'],
				'tax_rate'           => $data['rate'],
				'tax_rate_name'      => $data['name'],
				'tax_rate_priority'  => $data['priority'],
				'tax_rate_compound'  => $data['compound'],
				'tax_rate_shipping'  => $data['shipping'],
				'tax_rate_orderrder' => $data['order'],
				'tax_rate_class'     => $data['class'],
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

		foreach ( $data['locations'] as $location ) {
			$wpdb->replace(
				"{$wpdb->prefix}woocommerce_tax_rate_locations",
				array(
					'tax_rate_id'   => $id,
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
	}

}

if ( ! defined( 'WCD_TESTS' ) ) {
	new WOO_Tax();
}
