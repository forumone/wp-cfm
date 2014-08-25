<?php

class WPCFM_Helper
{

    /**
     * Load all bundles (DB + file)
     */
    function get_bundles() {
        $output = array();

        // Get DB bundles first
        $opts = WPCFM_Options::get( 'wpcfm_settings' );
        $opts = json_decode( $opts, true );
        foreach ( $opts['bundles'] as $bundle ) {
            $bundle['is_db'] = true;
            $bundle['is_file'] = false;
            $output[ $bundle['name'] ] = $bundle;
        }

        // Then merge file bundles
        $file_bundles = $this->get_file_bundles();
        foreach ( $file_bundles as $bundle_name => $bundle ) {
            if ( isset( $output[ $bundle_name ] ) ) {
                $output[ $bundle_name ]['is_file'] = true;
            }
            else {
                $bundle['is_db'] = false;
                $bundle['is_file'] = true;
                $output[ $bundle_name ] = $bundle;
            }
        }

        return $output;
    }


    /**
     * Get file bundles
     */
    function get_file_bundles() {
        $readwrite = new WPCFM_Readwrite();

        $output = array();
        $filenames = scandir( WPCFM_CONFIG_DIR );
        $filenames = array_diff( $filenames, array( '.', '..' ) );
        foreach ( $filenames as $filename ) {
            $bundle_name = str_replace( '.json', '', $filename );
            $bundle_data = $readwrite->read_file( $bundle_name );
            $bundle_label = $bundle_data['.label'];
            unset( $bundle_data['.label'] );

            $output[ $bundle_name ] = array(
                'label'     => $bundle_label,
                'name'      => $bundle_name,
                'config'    => $bundle_data,
            );
        }
        return $output;
    }


    /**
     * Load all bundle names
     */
    function get_bundle_names() {
        return array_keys( $this->get_bundles() );
    }


    /**
     * Get bundle by name
     */
    function get_bundle_by_name( $bundle_name ) {
        $bundles = $this->get_bundles();
        return isset( $bundles[ $bundle_name ] ) ?
            $bundles[ $bundle_name ] :
            array();
    }


    /**
     * Put configuration items into groups
     */
    function group_items( $items ) {

        $output = array();

        // Sort by array key
        ksort( $items );

        foreach ( $items as $key => $item ) {
            $group = isset( $item['group'] ) ? $item['group'] : __( 'WP Options', 'wpcfm' );
            $output[ $group ][ $key ] = $item;
        }

        return $output;
    }
}
