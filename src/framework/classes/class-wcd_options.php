<?php
/**
 * Class for handling options.
 *
 * @package woocart-defaults
 */
class WCD_Options {

    public $is_network = false;

    /**
     * Class Constructor.
     *
     * @access public
     * @since  1.0.0 
     */
    public function __construct() {
        // WP-CLI --network handler.
        $argv               = isset( $_SERVER['argv'] ) ? $_SERVER['argv'] : array();
        $has_network_flag   = ( false !== array_search( '--network', $argv ) );

        // Network admin.
        if ( is_network_admin() || $has_network_flag ) {
            $this->is_network = true;
        }
    }

    /**
     * Get settings (multi-site support)
     *
     * @access public
     */
    public function get( $option ) {
        return ( $this->is_network ) ?
            get_site_option( $option ) :
            get_option( $option );
    }

    /**
     * Update settings (multi-site support).
     *
     * @access public
     */
    public function update( $option, $value ) {
        return ( $this->is_network ) ? update_site_option( $option, $value ) : update_option( $option, $value );
    }

}
