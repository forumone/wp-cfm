<?php

class WPCFM_Readwrite
{
    public $registry;
    public $helper;
    public $folder;
    public $error;

    function __construct() {

        // Includes
        $this->registry = new WPCFM_Registry();
        $this->helper = new WPCFM_Helper();

        // Create the "wp-cfm" folder
        $this->folder = WP_CONTENT_DIR . '/config';

        if ( ! is_dir( $this->folder ) ) {
            if ( ! is_writable( $this->folder ) ) {
                $this->error = __( 'Create wp-content/config/ and grant write access', 'wpcfm' );
            }
            else {
                mkdir( $this->folder );
            }
        }
        elseif ( ! is_writable( $this->folder ) ) {
            $this->error = __( 'The wp-content/config/ folder is not writable', 'wpcfm' );
        }
    }


    /**
     * Move the file bundle to DB
     * @param string $bundle_name The bundle name (or "all")
     */
    function pull_bundle( $bundle_name ) {
        $bundles = ( 'all' == $bundle_name ) ? $this->helper->get_bundle_names() : array( $bundle_name );

        // Retrieve the settings
        $settings = get_option( 'wpcfm_settings' );
        $settings = json_decode( $settings, true );

        // Import each bundle into DB
        foreach ( $bundles as $bundle_name ) {
            $data = $this->read_file( $bundle_name );
            $this->write_db( $bundle_name, $data );

            // Update the bundle's config options (using the pull file)
            foreach ( $settings['bundles'] as $key => $bundle_settings ) {
                if ( $bundle_name == $bundle_settings['name'] ) {
                    $settings['bundles'][ $key ]['config'] = array_keys( $data );
                    break;
                }
            }
        }

        // Write the settings
        update_option( 'wpcfm_settings', json_encode( $settings ) );
    }


    /**
     * Move the DB bundle to file
     * @param string $bundle_name The bundle name (or "all")
     */
    function push_bundle( $bundle_name ) {
        $bundles = ( 'all' == $bundle_name ) ? $this->helper->get_bundle_names() : array( $bundle_name );

        foreach ( $bundles as $bundle_name ) {
            $data = $this->read_db( $bundle_name );

            // Append the bundle label
            $bundle_meta = $this->helper->get_bundle_by_name( $bundle_name );
            $data['.label'] = $bundle_meta['label'];

            // JSON_PRETTY_PRINT is only for PHP 5.4+
            $data = version_compare( PHP_VERSION, '5.4.0', '>=' ) ?
                json_encode( $data, JSON_PRETTY_PRINT ) :
                json_encode( $data );

            $this->write_file( $bundle_name, $data );
        }
    }


    /**
     * Compare the DB vs file versions
     */
    function compare_bundle( $bundle_name ) {

        $return = array();
        $db_bundle = array();
        $file_bundle = array();

        // Diff all bundles
        if ( 'all' == $bundle_name ) {
            $bundle_names = $this->helper->get_bundle_names();
            foreach ( $bundle_names as $bundle_name ) {

                // Retrieve each bundle
                $temp_file = $this->read_file( $bundle_name );
                $temp_db = $this->read_db( $bundle_name );

                // Merge the bundle values
                $file_bundle = array_merge( $file_bundle, $temp_file );
                $db_bundle = array_merge( $db_bundle, $temp_db );
            }
        }
        // Diff a single bundle
        else {
            $file_bundle = $this->read_file( $bundle_name );
            $db_bundle = $this->read_db( $bundle_name );
        }

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
        if ( is_readable( "$this->folder/$bundle_name.json" ) ) {
            $contents = file_get_contents( "$this->folder/$bundle_name.json" );
            return json_decode( $contents, true );
        }
        return array();
    }


    /**
     * Write the bundle to file
     */
    function write_file( $bundle_name, $data ) {
        $filename = "$this->folder/$bundle_name.json";
        if ( file_exists( $filename ) ) {
            if ( is_writable( $filename ) ) {
                return file_put_contents( $filename, $data );
            }
        }
        elseif ( is_writable( $this->folder ) ) {
            return file_put_contents( $filename, $data );
        }
        return false;
    }


    /**
     * Delete a bundle file
     */
    function delete_file( $bundle_name ) {
        if ( is_writable( "$this->folder/$bundle_name.json" ) ) {
            return unlink( "$this->folder/$bundle_name.json" );
        }
        return false;
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

            // Set a default group if needed
            $group = isset( $db_data[ $key ]['group'] ) ? $db_data[ $key ]['group'] : __( 'WP Options', 'wpcfm' );

            // Create the callback params
            $callback_params = array(
                'name' => $key,
                'group' => $group,
                'old_value' => $db_data[ $key ]['value'],
                'new_value' => $val,
            );

            // If no callback is defined, default to the "callback_wp_options" method
            $callback = array( $this, 'callback_wp_options' );
            if ( ! empty( $db_data[ $key ]['callback'] ) ) {
                $callback = $db_data[ $key ]['callback'];
            }

            // Allow for callback override
            $callback = apply_filters( 'wpcfm_pull_callback', $callback, $callback_params );

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
