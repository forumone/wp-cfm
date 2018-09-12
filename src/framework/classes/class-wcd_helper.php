<?php

namespace WooCart\WooCartDefaults;

/**
 * Helper class for YAML data.
 * (Not required currently but these might be helpful in future)
 *
 * @package woocart-defaults
 */

use Symfony\Component\Yaml\Yaml;

class WCD_Helper {


	/**
	 * Put configuration items into groups.
	 *
	 * @access public
	 */
	public function group_items( $items ) {
		$output = array();

		// Sort by array key.
		ksort( $items );

		foreach ( $items as $key => $item ) {
			if ( isset( $item['group'] ) ) {
				$output[ $group ][ $key ] = $item;
			}
		}

		return $output;
	}

	/**
	 * Convert array to yaml.
	 *
	 * @param $data array
	 * @param $saveFormat boolean
	 *
	 * @return mixed
	 * @access public
	 */
	public static function convert_to_yaml( $data, $saveFormat = true  ) {
		foreach ( $data as $key => &$value ) {
			$jsonDecoded = json_decode( $value, true );

			if ( is_array( $jsonDecoded ) ) {
				$value = $jsonDecoded;

				if ( $saveFormat ) {
					$data[ '.' . $key . '_format' ] = 'json';
				}
			} elseif ( is_serialized( $value ) ) {
				$value = unserialize( $value );

				if ( $saveFormat ) {
					$data[ '.' . $key . '_format' ] = 'serialized';
				}
			}
		}

		return Yaml::dump( $data, 10 );
	}

}
