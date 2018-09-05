<?php

namespace WooCart\WooCartDefaults;

/**
 * Class for handling options.
 *
 * @package woocart-defaults
 */

use Symfony\Component\Yaml\Yaml;

class WCD_Readwrite {

    const SETTINGNAME = 'WooCartDefaults.Settings';

    public $folder;
    public $error;

    /**
     * Class Constructor.
     *
     * @access public
     * @since  1.0.0 
     */
    public function __construct() {
        // Create the `config` folder.
        $this->folder = WCD_CONFIG_DIR;

        if ( ! is_dir( $this->folder ) ) {
            if ( ! wp_mkdir_p( $this->folder ) ) {
                $this->error = esc_html__( 'Create wp-content/config/ and grant write access', 'woocart-defaults' );
            }
        } elseif ( ! is_writable( $this->folder ) ) {
            $this->error = esc_html__( 'The wp-content/config/ folder is not writable', 'woocart-defaults' );
        }
    }

    /**
     * Move the file bundle to DB.
     *
     * @param string $bundle_name The bundle name (or "all")
     * @access public
     */
    public function pull_bundle( $bundle_name, $file_path ) {
        // Retrieve the settings.
        $settings = WCD()->options->get( self::SETTINGNAME );
        $settings = json_decode( $settings, true );

        // Is this really needed (and is it a good place?).
        if ( ! is_array( $settings ) || ! isset( $settings['bundles'] ) ) {
            $settings = array( 'bundles' => array() );
        }

        $dontUpdateSettings = false;

        // Import bundle into DB.
        $data = $this->read_file( $bundle_name, $file_path );

        // We received something :)
        if ( ! empty( $data ) ) {
            $this->write_db( $data );
        }        
    }

    /**
     * Move the DB bundle to file.
     *
     * @param string $bundle_name The bundle name (or "all")
     * @access public
     */
    public function push_bundle( $bundle_name, $file_path ) {
        $data = $this->read_db( $bundle_name );

        // Append the bundle label
        $bundle_meta    = WCD()->helper->get_bundle_by_name( $bundle_name );
        $data['.label'] = $bundle_meta['label'];

        if ( WCD_CONFIG_FORMAT == 'json' ) {
            // JSON_PRETTY_PRINT for PHP 5.4+
            $data = version_compare( PHP_VERSION, '5.4.0', '>=' ) ? json_encode( $data, JSON_PRETTY_PRINT ) : json_encode( $data );
        } elseif ( in_array( WCD_CONFIG_FORMAT, array( 'yaml', 'yml' ) ) ) {
            $data = WCD_Helper::convert_to_yaml( $data, true );
        }

        $this->write_file( $bundle_name, $file_path, $data );
    }

    /**
     * Compare the DB vs file versions.
     *
     * @access public
     */
    public function compare_bundle( $bundle_name ) {
        $return         = array();
        $db_bundle      = array();
        $file_bundle    = array();

        // Diff all bundles.
        if ( 'all' == $bundle_name ) {
            $bundle_names = WCD()->helper->get_bundle_names();

            foreach ( $bundle_names as $bundle_name ) {
                // Retrieve each bundle.
                $temp_file      = $this->read_file( $bundle_name );
                $temp_db        = $this->read_db( $bundle_name );

                // Merge the bundle values.
                $file_bundle    = array_merge( $file_bundle, $temp_file );
                $db_bundle      = array_merge( $db_bundle, $temp_db );
            }
        } else {
            // Diff a single bundle.
            $file_bundle    = $this->read_file( $bundle_name );
            $db_bundle      = $this->read_db( $bundle_name );
        }

        // Remove the .label.
        unset( $file_bundle['.label'] );

        // Convert to YAML for better readability if PHP version is compatible.
        if ( PHP_VERSION_ID >= 50604 && WCD_CONFIG_USE_YAML_DIFF ) {
            $file_bundle = WCD_Helper::convert_to_yaml( $file_bundle, false );
            $db_bundle   = WCD_Helper::convert_to_yaml( $db_bundle, false );
        }

        $return['error']    = '';
        $return['file']     = $file_bundle;
        $return['db']       = $db_bundle;

        return $return;
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
    public function read_file( $bundle_name, $file_path ) {
        $filename = $this->bundle_filename( $bundle_name );

        if ( file_exists( $file_path . $filename ) ) {
            if ( is_readable( $file_path . $filename ) ) {
                $contents = file_get_contents( $file_path . $filename );

                if ( WCD_CONFIG_FORMAT == 'json' ) {
                    return json_decode( $contents, true );
                } elseif ( in_array( WCD_CONFIG_FORMAT, array( 'yaml', 'yml' ) ) ) {
                    $array = Yaml::parse( $contents );

                    foreach ( $array as $key => $value ) {
                        $format = array();

                        if ( preg_match('/\.(.*)_format/i', $key, $format ) ) {
                            switch ( $array[$format[0]] ) {
                                case 'serialized':
                                    $array[$format[1]] = serialize( $array[$format[1]] );
                                    break;
                                case 'json':
                                    $array[$format[1]] = json_encode( $array[$format[1]] );
                                    break;
                            }

                            unset( $array[$format[0]] );
                        }
                    }

                    return $array;
                }
            }
        }

        return array();
    }

    /**
     * Write the bundle to file.
     *
     * @access public
     */
    public function write_file( $bundle_name, $file_path, $data ) {
        $filename = $this->bundle_filename( $bundle_name );

        if ( file_exists( $file_path . $filename ) ) {
            if ( is_writable( $file_path . $filename ) ) {
                return file_put_contents( $file_path . $filename, $data );
            }
        } elseif ( is_writable( $file_path ) ) {
            return file_put_contents( $file_path . $filename, $data );
        }

        return false;
    }

    /**
     * Delete a bundle file.
     *
     * @access public
     */
    public function delete_file( $bundle_name ) {
        $filename = $this->bundle_filename( $bundle_name );

        if ( is_writable( $filename ) ) {
            return unlink( $filename );
        }

        return false;
    }

    /**
     * Load the bundle (from database).
     *
     * @return array
     * @access public
     */
    public function read_db( $bundle_name ) {
        $output = array();
        $config = WCD()->registry->get_configuration_items();
        $opts   = WCD()->options->get( self::SETTINGNAME );
        $opts   = json_decode( $opts, true );

        foreach ( $opts['bundles'] as $bundle ) {
            if ( $bundle['name'] == $bundle_name ) {
                $bundle_config = $bundle['config'];
                break;
            }
        }

        if ( isset( $bundle_config ) ) {
            foreach ( $config as $key => $value ) {
                if ( in_array( $key, $bundle_config ) ) {
                    $output[ $key ] = $value['value'];
                }
            }
        }

        return $output;
    }

    /**
     * Save the bundle configuration data (to database).
     *
     * @param string $bundle_name
     * @param array $file_data Array of configuration items
     * @access public
     */
    public function write_db( $file_data ) {
        $success = false;
        $db_data = WCD()->registry->get_configuration_items();

        foreach ( $file_data as $key => $val ) {
            // Set a default group if needed.
            $group = isset( $db_data[ $key ]['group'] ) ? $db_data[ $key ]['group'] : esc_html__( 'WooCart Options', 'woocart-defaults' );

            // Make sure "old_value" exists.
            if ( empty( $db_data[ $key ]['value'] ) ) {
                $db_data[ $key ]['value'] = '';
            }

            // Create the callback params.
            $callback_params = array(
                'name'          => $key,
                'group'         => $group,
                'old_value'     => $db_data[ $key ]['value'],
                'new_value'     => $val,
            );

            // If no callback is defined, default to the "callback_wp_options" method.
            $callback = array( $this, 'callback_wp_options' );

            if ( ! empty( $db_data[$key]['callback'] ) ) {
                $callback = $db_data[$key]['callback'];
            }

            // Allow for callback override
            $callback = apply_filters( 'wcd_pull_callback', $callback, $callback_params );

            if ( is_callable( $callback ) ) {
                if ( is_array( $callback ) ) {
                    $function   = $callback[1];
                    $success    = $callback[0]->$function( $callback_params );
                } else {
                    $success = $callback( $callback_params );
                }
            }
        }

        return $success;
    }

    /**
     * Write to `wp_options` table.
     *
     * @access public
     */
    public function callback_wp_options( $params ) {
        $option_name    = $params['name'];
        $option_value   = maybe_unserialize( $params['new_value'] );

        WCD()->options->update( $option_name, $option_value );
    }

}
