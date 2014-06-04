<?php

class WPCFM_Helper
{

    /**
     * Load all bundles
     */
    function get_bundles() {
        $output = array();

        $opts = get_option( 'wpcfm_settings' );
        $opts = json_decode( $opts, true );
        foreach ( $opts['bundles'] as $bundle ) {
            $output[] = $bundle;
        }

        return $output;
    }


    /**
     * Load all bundle names
     */
    function get_bundle_names() {
        $output = array();

        $bundles = $this->get_bundles();
        foreach ( $bundles as $bundle ) {
            $output[] = $bundle['name'];
        }

        return $output;
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
