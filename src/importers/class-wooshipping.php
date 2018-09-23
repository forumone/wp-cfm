<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Value;

	/**
	 * Class WooTaxesValue
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooShippingZone extends Value {

		/**
		 * Return ShippingLocation array.
		 *
		 * @return iterable
		 */
		public function getLocations(): iterable {
			foreach ( $this->getZone()->locations as $location ) {
				$location          = ShippingLocation::fromArray( (array) $location );
				$location->zone_id = $this->getID();
				yield $location;
			}
		}

		/**
		 * Return zone like object.
		 *
		 * @return ShippingZone
		 */
		public function getZone(): ShippingZone {
			return ShippingZone::fromArray( $this->value );
		}

		/**
		 * Get tax id that was used in DB.
		 *
		 * @return int
		 */
		public function getID(): int {
			return intval( $this->getStrippedKey() );
		}

		/**
		 * Return ShippingMethod array.
		 *
		 * @return iterable
		 */
		public function getMethods(): iterable {
			foreach ( $this->getZone()->methods as $method ) {
				$method          = ShippingMethod::fromArray( (array) $method );
				$method->zone_id = $this->getID();
				yield $method;
			}
		}

		/**
		 * Enforce Zone structure by casting in and to array.
		 *
		 * @param array $values
		 */
		public function setZone( array $values ) {
			$zone = ShippingZone::fromArray( $values );
			$this->setValue( $zone->toArray() );
		}

		/**
		 * Sets value of WooCommerce tax option.
		 *
		 * @param array $values value to store in yaml file.
		 */
		public function setValue( array $values ) {
			$this->value = $values;
		}

	}

	/**
	 * Class ShippingLocation
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class ShippingLocation {


		use FromArray;
		use ToArray;

		/**
		 * @var int
		 */
		public $zone_id;
		/**
		 * @var string
		 */
		public $location_code;
		/**
		 * @var string
		 */
		public $location_type;
	}

	/**
	 * Class ShippingLocation
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class ShippingMethod {


		use FromArray;
		use ToArray;

		/**
		 * @var int
		 */
		public $zone_id;
		/**
		 * @var int
		 */
		public $method_order;
		/**
		 * @var bool
		 */
		public $is_enabled;
	}

	/**
	 * Class ShippingZone
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class ShippingZone {


		use FromArray;
		use ToArray;

		/**
		 * @var int
		 */
		public $zone_id;
		/**
		 * @var string
		 */
		public $name;
		/**
		 * @var int
		 */
		public $order;
		/**
		 * @var array
		 */
		public $locations;
		/**
		 * @var array
		 */
		public $methods;
	}

	/**
	 * WooCommerce shipping integration.
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooShipping implements Configuration {



		const namespace = 'wooship';

		/**
		 * Return importer specific Value instance.
		 *
		 * @param string $key Name of the kv pair.
		 * @param array  $value Value of the kv pair.
		 * @return WooShippingZone
		 */
		static function toValue( string $key, $value ): WooShippingZone {
			$val = new WooShippingZone( self::namespace );
			$val->setKey( $key );
			$val->setValue( $value );
			return $val;
		}

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

				$values = array(
					'name'      => $zone->zone_name,
					'order'     => $zone->zone_order,
					'locations' => $locations,
					'methods'   => $methods,
				);

				$value = new WooShippingZone( self::namespace );
				$value->setKey( $zone->zone_id );
				$value->setZone( $values );
				yield $value;
			}

		}

		/**
		 * Import (overwrite) shipping zones into the DB
		 *
		 * @param WooShippingZone $data
		 *
		 * @access public
		 */
		public function import( $data ) {
			global $wpdb;
			$inserted = false;
			$id       = $data->getID();
			$zone     = $data->getZone();

			$inserted &= $wpdb->insert(
				"{$wpdb->prefix}woocommerce_shipping_zones",
				array(
					'zone_id'    => $id,
					'zone_name'  => $zone->name,
					'zone_order' => $zone->order,
				),
				array(
					'%d',
					'%s',
					'%d',
				)
			);

			foreach ( $data->getLocations() as $location ) {
				$inserted &= $wpdb->insert(
					"{$wpdb->prefix}woocommerce_shipping_zone_locations",
					array(
						'zone_id'       => $id,
						'location_code' => $location->location_code,
						'location_type' => $location->location_type,
					),
					array(
						'%d',
						'%s',
						'%s',
					)
				);
			}

			foreach ( $data->getMethods() as $method ) {
				$inserted &= $wpdb->insert(
					"{$wpdb->prefix}woocommerce_shipping_zone_methods",
					array(
						'zone_id'      => $id,
						'method_order' => $method->method_order,
						'is_enabled'   => $method->is_enabled,
					),
					array(
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
			}
			return $inserted;
		}
	}
}
