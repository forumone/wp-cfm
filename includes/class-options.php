<?php

class WPCFM_Options
{

    public $is_network = false;


    function __construct() {

        // Network admin
        if ( is_network_admin() ) {
            $this->is_network = true;
        }

        // Called by wp-cli with the --network argument
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_multisite() ) {
            if ( preg_match( '#^' . network_admin_url() . '#i', $_SERVER['HTTP_REFERER'] ) ) {
                $this->is_network = true;
            }
        }
    }


    /**
     * Get the WP-CFM settings (multi-site support)
     * @since 1.3.0
     */
    function get( $option ) {
        return ( $this->is_network ) ?
            get_site_option( $option ) :
            get_option( $option );
    }


    /**
     * Update the WP-CFM settings (multi-site support)
     * @since 1.3.0
     */
    function update( $option, $value ) {
        return ( $this->is_network ) ?
            update_site_option( $option, $value ) :
            update_option( $option, $value );
    }
}
