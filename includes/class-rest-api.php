<?php

class WPCFM_RESTAPI
{
    function __construct() {

        add_action( 'rest_api_init', function () {
            register_rest_route( 'wpcfm/v1', '/settings', array(
                'methods' => 'GET',
                'callback' => array($this, 'load_settings'),
                'permission_callback' => array($this, '_perms_cb'),
            ) );
            register_rest_route( 'wpcfm/v1', '/settings', array(
                'methods' => 'POST',
                'callback' => array($this, 'save_settings'),
                'permission_callback' => array($this, '_perms_cb'),
            ) );
            register_rest_route( 'wpcfm/v1', '/diff/(?P<name>[\\w-_]+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'load_diff'),
                'permission_callback' => array($this, '_perms_cb'),
            ) );
            register_rest_route( 'wpcfm/v1', '/push/(?P<name>[\\w-_]+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'push_bundle'),
                'permission_callback' => array($this, '_perms_cb'),
            ) );
            register_rest_route( 'wpcfm/v1', '/pull/(?P<name>[\\w-_]+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'pull_bundle'),
                'permission_callback' => array($this, '_perms_cb'),
            ) );
            register_rest_route( 'wpcfm/v1', '/bundle/(?P<name>[\\w-_]+)', array(
                'methods' => 'POST',
                'callback' => array($this, 'upload_bundle'),
                'permission_callback' => array($this, '_perms_cb'),
            ) );
        } );
    }


    function _perms_cb() {
        return current_user_can( 'manage_options' );
    }


    /**
     * Load admin settings
     */
    function load_settings($request) {
        $bundles = WPCFM()->helper->get_bundles();
        return array( 'bundles' => $bundles );
    }


    /**
     * Save admin settings
     */
    function save_settings($request) {
        $settings = stripslashes( $request['data'] );

        // Save the option
        WPCFM()->options->update( 'wpcfm_settings', $settings );

        // Delete orphan bundles
        $file_bundles = array_keys( WPCFM()->helper->get_file_bundles() );
        $new_bundles = WPCFM()->helper->get_bundles();

        foreach ( $file_bundles as $bundle_name ) {
            if ( ! isset( $new_bundles[ $bundle_name ] ) || false === $new_bundles[ $bundle_name ]['is_db'] ) {
                WPCFM()->readwrite->delete_file( $bundle_name );
            }
        }

        return __( 'Settings saved', 'wpcfm' );
    }


    function load_diff($request) {
        $bundle_name = stripslashes( $request['name'] );
        $comparison = WPCFM()->readwrite->compare_bundle( $bundle_name );

        // The pretty-text-diff.js will do its best on these print_r()s.
        if ( isset( $comparison['file'] ) ) {
            $comparison['file'] = print_r( $comparison['file'], true );
        }
        if ( isset( $comparison['db'] ) ) {
            $comparison['db'] = print_r( $comparison['db'], true );
        }

        return json_encode( $comparison );
    }


    /**
     * Push settings to filesystem
     */
    function push_settings($request) {
        $bundle_name = stripslashes( $request['name'] );
        WPCFM()->readwrite->push_bundle( $bundle_name );
        return __( 'Push successful', 'wpcfm' );
    }


    /**
     * Pull settings into DB
     */

    function pull_settings($request) {
        $bundle_name = stripslashes( $request['name'] );
        WPCFM()->readwrite->pull_bundle( $bundle_name );
        return __( 'Pull successful', 'wpcfm' );
    }


    /**
     * Accept uploaded bundle to filesystem
     */
    function upload_bundle($request) {
        $bundle_name = $request['name'];
        $file_content = $request['file_content'];
        WPCFM()->upload->upload_bundle( $bundle_name, $file_content );
        if(WPCFM()->upload->error) {
            return new WP_Error( 'error', WPCFM()->upload->error, array( 'status' => 500 ) );
        }
        else {
            return __( 'Upload successful', 'wpcfm' );
        }
    }
}
