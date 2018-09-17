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
	class Importer {


		/**
		 * Move the file bundle to DB.
		 *
		 * @param string $file_path Path to the bundle.
		 * @return bool|mixed
		 * @access public
		 * @throws \Exception
		 */
		public function import( $file_path ) {
			$data = $this->read_file( $file_path );
			$this->parse( $data );

			return true;
		}

		/**
		 * Load the file bundle.
		 *
		 * @param $file_path
		 * @return array
		 * @access public
		 */
		public function read_file( $file_path ) {
			$contents = file_get_contents( $file_path );

			$array = Yaml::parse( $contents );

			foreach ( $array as $key => $value ) {
				$format = array();

				if ( preg_match( '/\.(.*)_format/i', $key, $format ) ) {
					switch ( $array[ $format[0] ] ) {
						case 'serialized':
							$array[ $format[1] ] = serialize( $array[ $format[1] ] );
							break;
						case 'json':
							$array[ $format[1] ] = json_encode( $array[ $format[1] ] );
							break;
					}

					unset( $array[ $format[0] ] );
				}
			}

			return $array;

		}


		/**
		 * @param $key string name of the yaml kv pair.
		 * @return Configuration
		 * @throws \Exception when importer is not found.
		 */
		public function resolve( $key ): Configuration {
			$importers = ConfigsRegistry::get();
			foreach ( $importers as $importer ) {
				if ( explode( '/', $key )[0] === $importer::namespace ) {
					return $importer;
				}
			}

			throw new \Exception( "Importer for $key is not found." );
		}

		/**
		 * @param array $data
		 * @throws \Exception
		 */
		public function parse( array $data ) {
			if ( ! empty( $data ) ) {
				if ( is_array( $data ) ) {
					foreach ( $data as $key => $val ) {
						$importer = $this->resolve( $key );
						$importer->import( $importer->toValue( $key, $val ) );
					}
				}
			}
		}

	}
}
