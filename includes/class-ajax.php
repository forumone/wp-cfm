<?php

class WPCFM_Ajax
{

    public $readwrite;
    public $helper;

    function __construct() {

        $this->readwrite = new WPCFM_Readwrite();
        $this->helper = new WPCFM_Helper();

        add_action( 'wp_ajax_wpcfm_load', array( $this, 'load_settings' ) );
        add_action( 'wp_ajax_wpcfm_save', array( $this, 'save_settings' ) );
        add_action( 'wp_ajax_wpcfm_push', array( $this, 'push_settings' ) );
        add_action( 'wp_ajax_wpcfm_pull', array( $this, 'pull_settings' ) );
        add_action( 'wp_ajax_wpcfm_diff', array( $this, 'load_diff' ) );
    }


    /**
     * Load admin settings
     */
    function load_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundles = $this->helper->get_bundles();
            echo json_encode( array( 'bundles' => $bundles ) );
        }
        exit;
    }


    /**
     * Save admin settings
     */
    function save_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $settings = stripslashes( $_POST['data'] );

            // Store old bundle names for use later
            $old_bundles = $this->helper->get_bundle_names();

            // Save the option
			WPCFM_Options::update( 'wpcfm_settings', $settings );

            // Delete orphan bundles
            $new_bundles = $this->helper->get_bundle_names();
            $to_delete = array_diff( $old_bundles, $new_bundles );
            foreach ( $to_delete as $bundle_name ) {
                $this->readwrite->delete_file( $bundle_name );
            }

            echo __( 'Settings saved', 'wpcfm' );
        }
        exit;
    }


    function load_diff() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundle_name = stripslashes( $_POST['data']['bundle_name'] );
            echo json_encode( $this->readwrite->compare_bundle( $bundle_name ) );
        }
        exit;
    }


    /**
     * Push settings to filesystem
     */
    function push_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundle_name = stripslashes( $_POST['data']['bundle_name'] );
            $this->readwrite->push_bundle( $bundle_name );
            echo __( 'Push successful', 'wpcfm' );
        }
        exit;
    }


    /**
     * Pull settings into DB
     */
    function pull_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundle_name = stripslashes( $_POST['data']['bundle_name'] );
            $this->readwrite->pull_bundle( $bundle_name );
            echo __( 'Pull successful', 'wpcfm' );
        }
        exit;
    }
}
