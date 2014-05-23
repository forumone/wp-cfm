<?php

class WPCFM_Helper
{

    /**
     * Load all bundle names
     */
    function get_bundle_names() {
        $output = array();

        $opts = get_option( 'wpcfm_settings' );
        $opts = json_decode( $opts, true );
        foreach ( $opts['bundles'] as $bundle ) {
            $output[] = $bundle['name'];
        }

        return $output;
    }
}
