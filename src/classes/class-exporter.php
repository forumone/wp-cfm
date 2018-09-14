<?php
/**
 * Handles imports from yaml.
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage woocart-defaults
 * @since      1.0.0
 */

namespace Niteo\WooCart\Defaults {

	use Niteo\WooCart\Defaults\Importers\Configuration;
	use Symfony\Component\Yaml\Yaml;


	/**
	 * Class Importer
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class Exporter {


		/**
		 * Convert array|serialized to yaml.
		 *
		 * @param $data array
		 * @return mixed
		 * @access public
		 */
		public static function convert_to_yaml( $data ) {
			foreach ( $data as $key => &$value ) {
				$jsonDecoded = json_decode( $value, true );

				if ( is_array( $jsonDecoded ) ) {
					$value                          = $jsonDecoded;
					$data[ '.' . $key . '_format' ] = 'json';
				} elseif ( is_serialized( $value ) ) {
					$value                          = unserialize( $value );
					$data[ '.' . $key . '_format' ] = 'serialized';
				}
			}

			return Yaml::dump( $data, 10 );
		}

		/**
		 * Move the DB to file bundle.
		 *
		 * @param string $file_path Path to the bundle.
		 * @return bool|mixed
		 * @access public
		 * @throws \Exception
		 */
		public function export( $file_path ) {

			return true;
		}

	}
}
