<?php

class WPCFM_Ajax
{

    function __construct() {
        // ajax settings
        add_action( 'wp_ajax_wpcfm_load', array( $this, 'load_settings' ) );
        add_action( 'wp_ajax_wpcfm_save', array( $this, 'save_settings' ) );
    }


    /**
     * Load admin settings
     */
    function load_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            echo get_option( 'wpcfm_settings' );
        }
        exit;
    }


    /**
     * Save admin settings
     */
    function save_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $settings = stripslashes( $_POST['data'] );
            update_option( 'wpcfm_settings', $settings );
            echo __( 'Settings saved', 'wpcfm' );
        }
        exit;
    }
}
