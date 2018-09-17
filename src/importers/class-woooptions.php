<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Value;


	/**
	 * Class WooOptionsValue
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooOptionsValue extends Value {


		/**
		 *  Group name used in UI or tables.
		 */
		const group = 'WooCommerce Options';

		/**
		 * Sets value of WooCommerce option.
		 *
		 * @param string $option_value value to store in yaml file.
		 */
		public function setValue( string $option_value ) {
			$this->value = $option_value;
		}

	}

	/**
	 * WooCommerce options integration.
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooOptions implements Configuration {



		/**
		 *  This importer namespace.
		 */
		const namespace = 'woo';

		/**
		 * Return importer specific Value.
		 *
		 * @param string $key Name of the kv pair.
		 * @param string $value Value of the kv pair.
		 * @return WooOptionsValue
		 */
		static function toValue( string $key, $value ): WooOptionsValue {
			$val = new WooOptionsValue( self::namespace );
			$val->setKey( $key );
			$val->setValue( $value );
			return $val;
		}

		/**
		 * Get WooCommerce configuration items from the database.
		 *
		 * @access public
		 * @return mixed
		 */
		public function items(): iterable {
			global $wpdb;

			$query = $wpdb->prepare(
				"SELECT option_name, option_value FROM $wpdb->options
            WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
            AND option_name LIKE 'woocommerce_%'
            ORDER BY option_name"
			);

			$results = $wpdb->get_results( $query );

			foreach ( $results as $op ) {
				$value = new WooOptionsValue( self::namespace );
				$value->setKey( $op->option_name );
				$value->setValue( $op->option_value );
				yield $value;
			}
		}

		/**
		 * Import (overwrite) WooCart specific settings in the DB.
		 *
		 * @param WooOptionsValue $value kv object for update or insert.
		 * @access public
		 */
		public function import( $value ) {
			update_option( $value->getStrippedKey(), $value->getValue() );
		}
	}
}
