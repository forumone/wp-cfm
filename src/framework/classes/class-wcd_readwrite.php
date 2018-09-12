<?php

namespace WooCart\WooCartDefaults;

/**
 * Class for handling options.
 *
 * @package woocart-defaults
 */

use Symfony\Component\Yaml\Yaml;

class WCD_Readwrite {


	/**
	 * Move the file bundle to DB.
	 *
	 * @param  string $bundle_name The bundle name (or "all")
	 * @access public
	 */
	public function pull_bundle( $bundle_name, $file_path ) {
		$response = false;

		// Read file.
		$file_name = $this->bundle_filename( $bundle_name );

		if ( file_exists( $file_path . $file_name ) ) {
			if ( is_readable( $file_path . $file_name ) ) {
				$data = $this->read_file( $file_name, $file_path );

				// We received something :)
				if ( ! empty( $data ) ) {
					if ( is_array( $data ) ) {
						$response = $this->write_db( $data );
					}
				}
			}
		}

		return $response;
	}

	/**
	 * Returns the bundle filename.
	 *
	 * @return string
	 * @access public
	 */
	public function bundle_filename( $bundle_name ) {
		$filename = $bundle_name . '.' . WCD_CONFIG_FORMAT;
		return $filename;
	}

	/**
	 * Load the file bundle.
	 *
	 * @return array
	 * @access public
	 */
	public function read_file( $file_name, $file_path ) {
		$contents = file_get_contents( $file_path . $file_name );

		if ( WCD_CONFIG_FORMAT == 'json' ) {
			return json_decode( $contents, true );
		} elseif ( in_array( WCD_CONFIG_FORMAT, array( 'yaml', 'yml' ) ) ) {
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

		return array();
	}

	/**
	 * Save the bundle configuration data (to database).
	 *
	 * @param  string $bundle_name
	 * @param  array  $file_data   Array of configuration items
	 * @access public
	 */
	public function write_db( $file_data ) {
		$success = false;
		$db_data = WCD()->registry->get_configuration_items();

		foreach ( $file_data as $key => $val ) {
			// Set a default group if needed.
			$group = isset( $db_data[ $key ]['group'] ) ? $db_data[ $key ]['group'] : esc_html__( 'Undefined Options', 'woocart-defaults' );

			// Make sure "old_value" exists.
			if ( empty( $db_data[ $key ]['value'] ) ) {
				$db_data[ $key ]['value'] = '';
			}

			// Create the callback params.
			$callback_params = array(
				'name'      => $key,
				'group'     => $group,
				'old_value' => $db_data[ $key ]['value'],
				'new_value' => $val,
			);

			// If no callback is defined, default to the "callback_wp_options" method.
			$callback = array( &$this, 'callback_wp_options' );

			if ( ! empty( $db_data[ $key ]['callback'] ) ) {
				   $callback = $db_data[ $key ]['callback'];
			}

			// Allow for callback override
			$callback = apply_filters( 'wcd_pull_callback', $callback, $callback_params );

			if ( is_callable( $callback ) ) {
				if ( is_array( $callback ) ) {
					$function = $callback[1];
					$success  = $callback[0]->$function( $callback_params );
				} else {
					$success = $callback( $callback_params );
				}

				$success = true;
			}
		}

		return $tmp;
	}

	/**
	 * Write to `wp_options` table.
	 *
	 * @access public
	 */
	public function callback_wp_options( $params ) {
		$option_name  = $params['name'];
		$option_value = maybe_unserialize( $params['new_value'] );

		update_option( $option_name, $option_value );
	}

}
