<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Value;

	/**
	 * Class WooTaxesValue
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooTaxesValue extends Value {



		/**
		 *  Group name used in UI or tables.
		 */
		const group = 'WooCommerce Taxes';

		/**
		 * Sets value of WooCommerce tax option.
		 *
		 * @param array $values value to store in yaml file.
		 */
		public function setValue( array $values ) {
			$this->value = $values;
		}

		/**
		 * Return tax like object.
		 *
		 * @return Tax
		 */
		public function getTax(): Tax {
			return Tax::fromArray( $this->value );
		}

		/**
		 * Return Locations array.
		 *
		 * @return iterable
		 */
		public function getLocations(): iterable {
			foreach ( $this->getTax()->locations as $location ) {
				$location              = Location::fromArray( (array) $location );
				$location->tax_rate_id = $this->getID();
				yield $location;
			}
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
		 * Enforce Tax structure by casting in and to array.
		 *
		 * @param array $values
		 */
		public function setTax( array $values ) {
			$tax = Tax::fromArray( $values );
			$this->setValue( $tax->toArray() );
		}

	}


	/**
	 * Class Tax
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class Tax {


		use FromArray;
		use ToArray;

		/**
		 * @var string
		 */
		public $country;
		/**
		 * @var string
		 */
		public $state;
		/**
		 * @var int
		 */
		public $rate;
		/**
		 * @var string
		 */
		public $name;
		/**
		 * @var int
		 */
		public $priority;
		/**
		 * @var int
		 */
		public $compound;
		/**
		 * @var string
		 */
		public $shipping;
		/**
		 * @var int
		 */
		public $order;
		/**
		 * @var string
		 */
		public $class;
		/**
		 * @var array
		 */
		public $locations;
	}

	/**
	 * Class Location
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class Location {


		use FromArray;
		use ToArray;

		/**
		 * @var int
		 */
		public $tax_rate_id;
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
	 * WooCommerce tax rates.
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooTaxes implements Configuration {



		/**
		 * Namespace for this importer.
		 */
		const namespace = 'wootax';

		/**
		 * Register the tax rates in WCD.
		 *
		 * @access public
		 */
		public function items(): iterable {
			global $wpdb;

			$query     = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates" );
			$tax_rates = $wpdb->get_results( $query );

			foreach ( $tax_rates as $tax ) {

				$query = $wpdb->prepare(
					"
                SELECT location_code, location_type 
                FROM {$wpdb->prefix}woocommerce_tax_rate_locations
				WHERE tax_rate_id = %d",
					$tax->tax_rate_id
				);

				$locations = $wpdb->get_results( $query, 'ARRAY_A' );

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
				$value  = new WooTaxesValue( self::namespace );
				$value->setKey( $tax->tax_rate_id );
				$value->setTax( $values );
				yield $value;
			}
		}


		/**
		 * Import (overwrite) tax rates into the DB
		 *
		 * @param WooTaxesValue $data Value
		 *
		 * @access public
		 */
		public function import( $data ) {
			global $wpdb;

			$id  = $data->getID();
			$tax = $data->getTax();

			$wpdb->replace(
				$wpdb->prepare( "{$wpdb->prefix}woocommerce_tax_rates" ),
				array(
					'tax_rate_id'       => $id,
					'tax_rate_country'  => $tax->country,
					'tax_rate_state'    => $tax->state,
					'tax_rate'          => $tax->rate,
					'tax_rate_name'     => $tax->name,
					'tax_rate_priority' => $tax->priority,
					'tax_rate_compound' => $tax->compound,
					'tax_rate_shipping' => $tax->shipping,
					'tax_rate_order'    => $tax->order,
					'tax_rate_class'    => $tax->class,
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

			foreach ( $tax->locations as $location ) {
				$wpdb->replace(
					$wpdb->prepare( "{$wpdb->prefix}woocommerce_tax_rate_locations" ),
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

		/**
		 * Return importer specific Value instance.
		 *
		 * @param string $key Name of the kv pair.
		 * @param array  $value Value of the kv pair.
		 * @return WooTaxesValue
		 */
		static function toValue( string $key, $value ): WooTaxesValue {
			$val = new WooTaxesValue( self::namespace );
			$val->setKey( $key );
			$val->setValue( $value );
			return $val;
		}
	}
}
