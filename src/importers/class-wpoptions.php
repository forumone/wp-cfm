<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Value;

	/**
	 * Class WPOptionsValue
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WPOptionsValue extends Value {

		/**
		 *  Group name used in UI or tables.
		 */
		const group = 'WordPress Options';

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
	 * WordPress options.
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WPOptions implements Configuration {


		/**
		 *  This importer namespace.
		 */
		const namespace = 'wp';

		/**
		 * Get WordPress configuration items from the database.
		 *
		 * @access public
		 * @return mixed
		 */
		public function items(): iterable {
			global $wpdb;

			$query = $wpdb->prepare(
				"SELECT option_name, option_value FROM $wpdb->options
            WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
            AND option_name NOT LIKE 'woocommerce_%'
            ORDER BY option_name"
			);

			$results = $wpdb->get_results( $query );

			foreach ( $results as $op ) {
				$value = new WooOptionsValue( self::namespace );
				$value->setName( $op->option_name );
				$value->setValue( $op->option_value );
				yield $value;
			}
		}


		/**
		 * Import (overwrite) WordPress core specific settings in the DB.
		 *
		 * @param Value $value kv object for update or insert.
		 * @access public
		 */
		public function import( $value ) {
			update_option( $value->getStrippedName(), $value->getValue() );
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
		 * @return WPOptionsValue
		 */
		public function toValue( string $key, $value ): WPOptionsValue {
			$val = new WPOptionsValue( self::namespace );
			$val->setName( $key );
			$val->setValue( $value );
			return $val;
		}
	}
}
