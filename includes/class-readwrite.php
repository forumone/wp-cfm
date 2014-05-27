<?php

class WPCFM_Readwrite
{
    public $folder;
    public $error;

    function __construct() {

        // Create the "wp-cfm" folder
        $this->folder = WP_CONTENT_DIR . '/config';

        if ( ! is_dir( $this->folder ) ) {
            if ( ! is_writable( $this->folder ) ) {
                $this->error = __( 'Create /wp-content/config/ and grant write access', 'wpcfm' );
            }
            mkdir( $this->folder );
        }
        elseif ( ! is_writable( $this->folder ) ) {
            $this->error = __( 'The /wp-content/config/ folder is not writable', 'wpcfm' );
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
        $registry = new WPCFM_Registry();
        $all_config = $registry->get_configuration_items();

        $opts = get_option( 'wpcfm_settings' );
        $opts = json_decode( $opts, true );
        foreach ( $opts['bundles'] as $bundle ) {
            if ( $bundle['name'] == $bundle_name ) {
                $bundle_config = $bundle['config'];
                break;
            }
        }

        foreach ( $all_config as $namespace => $config_items ) {
            foreach ( $config_items as $key => $val ) {
                if ( in_array( $key, $bundle_config ) ) {
                    $output[ $namespace ][ $key ] = $val;
                }
            }
        }

        return $output;
    }


    /**
     * Save the bundle configuration data (to database)
     * Figure out how to handle DB writes
     * @todo support custom (3rd party) write handlers
     */
    function write_db( $bundle_name, $file_data ) {

        $success = false;
        $registry = new WPCFM_Registry();
        $db_data = $registry->get_configuration_items();

        foreach ( $file_data as $namespace => $config_items ) {
            foreach ( $config_items as $key => $val ) {

                // Handle each input value
                $callback = array( $this, 'callback_wp_options' );
                $callback = apply_filters( 'wpcfm_pull_handler', $callback, $key );
                $callback_params = array(
                    'option_name' => $key,
                    'namespace' => $namespace,
                    'old_data' => $db_data[ $namespace ][ $key ],
                    'new_data' => $val,
                );

                if ( is_callable( $callback ) ) {
                    if ( is_array( $callback ) ) {
                        $success = $callback[0]->$callback[1]( $callback_params );
                    }
                    else {
                        $success = $callback( $callback_params );
                    }
                }
            }
        }

        return $success;
    }


    /**
     * Default callback - write to wp_options table
     */
    function callback_wp_options( $params ) {
        $option_name = $params['option_name'];
        $option_value = maybe_unserialize( $params['new_data'] );
        update_option( $option_name, $option_value );
    }
}
