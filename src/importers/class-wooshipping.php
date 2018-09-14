<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Value;

	/**
	 * WooCommerce shipping integration.
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooShipping implements Configuration {

		const namespace = 'wooship';

		/**
		 * Register the shipping zones in WCD.
		 *
		 * @access public
		 * @return mixed
		 */
		public function items(): iterable {
			global $wpdb;

			$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_shipping_zones" );
			$zones = $wpdb->get_results( $query );
			$items = [];
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

				$items[ $zone->zone_id ] = array(
					'value' => json_encode( $value ),
					'label' => $zone->zone_name,
					'group' => 'WooCommerce Shipping Zones',
				);

			}

			return $items;
		}


		/**
		 * Import (overwrite) shipping zones into the DB
		 *
		 * @param Value $params
		 *
		 * @access public
		 */
		public function import( $params ) {
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

		/**
		 * Namespace of this importer.
		 *
		 * @return string This objects namespace.
		 */
		public function getNamespace(): string {
			return self::namespace;
		}

		/**
		 * Return importer specific Value instance.
		 *
		 * @param string $key Name of the kv pair.
		 * @param string $value Value of the kv pair.
		 * @return WooOptionsValue
		 */
		public function toValue( string $key, $value ): WooOptionsValue {
			$val = new WooOptionsValue( self::namespace );
			$val->setName( $key );
			$val->setValue( $value );
			return $val;
		}
	}
}
