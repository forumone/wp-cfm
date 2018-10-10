<?php

namespace Niteo\WooCart\Defaults\Importers {


	/**
	 * WooCommerce options integration.
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooOptions extends WPOptions {


		/**
		 *  This importer namespace.
		 */
		const namespace = 'woo';

		/**
		 * Get WooCommerce configuration items from the database.
		 *
		 * @access public
		 * @return mixed
		 */
		public function items(): iterable {
			global $wpdb;

			$query = "SELECT option_name, option_value FROM $wpdb->options
            WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
            AND option_name LIKE 'woocommerce_%'
            ORDER BY option_name";

			$results = $wpdb->get_results( $query );

			foreach ( $results as $op ) {
				$value = new WPOptionsValue( self::namespace );
				$value->setKey( $op->option_name );
				$value->setValue( $op->option_value );
				yield $value;
			}
		}

		/**
		 * Return importer specific Value instance.
		 *
		 * @param string $key Name of the kv pair.
		 * @param string $value Value of the kv pair.
		 * @return WPOptionsValue
		 */
		static function toValue( string $key, $value ): WPOptionsValue {
			$val = new WPOptionsValue( self::namespace );
			$val->setKey( $key );
			$val->setValue( $value );
			return $val;
		}

	}
}
