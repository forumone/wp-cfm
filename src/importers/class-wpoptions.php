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

		/**
		 * Get WordPress configuration items from the database.
		 *
		 * @access public
		 * @return mixed
		 */
		public function items(): iterable {
			global $wpdb;

			$query = "SELECT option_name, option_value FROM $wpdb->options
            WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
            AND option_name NOT LIKE 'woocommerce_%'
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
		 * Import (overwrite) WordPress core specific settings in the DB.
		 *
		 * @param WPOptionsValue $data kv object for update or insert.
		 * @access public
		 * @return bool
		 */
		public function import( $data ): bool {
			global $wpdb;
			$option = $data->getStrippedKey();
			$value  = $data->getValue();

			$old_value = get_option( $option );

			if ( $old_value === false ) {
				fwrite( STDOUT, "Inserting $option: $value" . PHP_EOL );
				return $wpdb->insert(
					$wpdb->options,
					[
						'option_value' => $value,
						'autoload'     => 'yes',
						'option_name'  => $option,
					],
					[ '%s', '%s', '%s' ]
				);
			}
			fwrite( STDOUT, "Updating $option: $value" . PHP_EOL );
			return $wpdb->update(
				$wpdb->options,
				[ 'option_value' => $value ],
				[ 'option_name' => $option ]
			);

		}
	}
}
