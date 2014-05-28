<?php

class WPCFM_Readwrite
{
    public $registry;
    public $folder;
    public $error;

    function __construct() {

        // Includes
        $this->registry = new WPCFM_Registry();

        // Create the "wp-cfm" folder
        $this->folder = WP_CONTENT_DIR . '/config';

        if ( ! is_dir( $this->folder ) ) {
            if ( ! is_writable( $this->folder ) ) {
                $this->error = __( 'Create wp-content/config/ and grant write access', 'wpcfm' );
            }
            mkdir( $this->folder );
        }
        elseif ( ! is_writable( $this->folder ) ) {
            $this->error = __( 'The wp-content/config/ folder is not writable', 'wpcfm' );
        }
    }


    /**
     * Move the file bundle to DB
     * Pull is difficult; we need to figure out how to properly
     * import each setting into the database
     */
    function pull_bundle( $bundle_name ) {
        $data = $this->read_file( $bundle_name );
        $this->write_db( $bundle_name, $data );
    }


    /**
     * Move the DB bundle to file
     * Push is easy; we simply write data to file
     */
    function push_bundle( $bundle_name ) {
        $data = $this->read_db( $bundle_name );
        $this->write_file( $bundle_name, json_encode( $data, JSON_PRETTY_PRINT ) );
    }


    /**
     * Compare the DB vs file versions
     */
    function compare_bundle( $bundle_name ) {

        $return = array();
        $file_bundle = $this->read_file( $bundle_name );
        $db_bundle = $this->read_db( $bundle_name );

        if ( $file_bundle == $db_bundle ) {
            $return['error'] = __( 'Both versions are identical', 'wpcfm' );
        }
        else {
            $return['error'] = '';
            $return['file'] = print_r( $file_bundle, true );
            $return['db'] = print_r( $db_bundle, true );
        }

        return $return;
    }


    /**
     * Load the file bundle
     * @return array
     */
    function read_file( $bundle_name ) {
        if ( file_exists( "$this->folder/$bundle_name.json" ) ) {
            $contents = file_get_contents( "$this->folder/$bundle_name.json" );
            return json_decode( $contents, true );
        }
        return array();
    }


    /**
     * Write the bundle to file
     */
    function write_file( $bundle_name, $data ) {
        return file_put_contents( "$this->folder/$bundle_name.json", $data );
    }


    /**
     * Load the bundle (from database)
     * @return array
     */
    function read_db( $bundle_name ) {

        $output = array();
        $all_config = $this->registry->get_configuration_items();

        $opts = get_option( 'wpcfm_settings' );
        $opts = json_decode( $opts, true );
        foreach ( $opts['bundles'] as $bundle ) {
            if ( $bundle['name'] == $bundle_name ) {
                $bundle_config = $bundle['config'];
                break;
            }
        }

        foreach ( $all_config as $key => $config ) {
            if ( in_array( $key, $bundle_config ) ) {
                $output[ $key ] = $config['value'];
            }
        }

        return $output;
    }


    /**
     * Save the bundle configuration data (to database)
     * @param string $bundle_name
     * @param array $file_data Array of configuration items
     */
    function write_db( $bundle_name, $file_data ) {

        $success = false;
        $db_data = $this->registry->get_configuration_items();

        foreach ( $file_data as $key => $val ) {

            // Create the callback params
            $callback_params = array(
                'name' => $key,
                'group' => $db_data[ $key ]['group'],
                'old_value' => $db_data[ $key ]['value'],
                'new_value' => $val,
            );

            // If no callback is defined, default to the "callback_wp_options" method
            $callback = array( $this, 'callback_wp_options' );
            if ( ! empty( $db_data[ $key ]['callback'] ) ) {
                $callback = $db_data[ $key ]['callback'];
            }

            if ( is_callable( $callback ) ) {
                if ( is_array( $callback ) ) {
                    $success = $callback[0]->$callback[1]( $callback_params );
                }
                else {
                    $success = $callback( $callback_params );
                }
            }
        }

        return $success;
    }


    /**
     * Default callback - write to wp_options table
     */
    function callback_wp_options( $params ) {
        $option_name = $params['name'];
        $option_value = maybe_unserialize( $params['new_value'] );
        update_option( $option_name, $option_value );
    }
}
