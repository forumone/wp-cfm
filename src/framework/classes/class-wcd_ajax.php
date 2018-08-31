<?php

namespace WooCart\WooCartDefaults;

/**
 * Class for hnadling AJAX requests.
 *
 * @package woocart-defaults
 */
class WCD_Ajax {

    const SETTINGNAME = 'WooCartDefaults.Settings';

    /**
     * Class Constructor.
     *
     * @access public
     * @since  1.0.0 
     */
    public function __construct() {
        add_action( 'wp_ajax_wcd_load', array( &$this, 'load_settings' ) );
        add_action( 'wp_ajax_wcd_save', array( &$this, 'save_settings' ) );
        add_action( 'wp_ajax_wcd_push', array( &$this, 'push_settings' ) );
        add_action( 'wp_ajax_wcd_pull', array( &$this, 'pull_settings' ) );
        add_action( 'wp_ajax_wcd_diff', array( &$this, 'load_diff' ) );
    }

    /**
     * Load admin settings.
     *
     * @access public
     */
    public function load_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundles = WCD()->helper->get_bundles();
            echo json_encode( array( 'bundles' => $bundles ) );
        }

        exit;
    }

    /**
     * Save admin settings.
     *
     * @access public
     */
    public function save_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $settings = stripslashes( $_POST['data'] );

            // Save the option
            WCD()->options->update( self::SETTINGNAME, $settings );

            // Delete orphan bundles
            $file_bundles   = array_keys( WCD()->helper->get_file_bundles() );
            $new_bundles    = WCD()->helper->get_bundles();

            foreach ( $file_bundles as $bundle_name ) {
                if ( ! isset( $new_bundles[ $bundle_name ] ) || false === $new_bundles[ $bundle_name ]['is_db'] ) {
                    WCD()->readwrite->delete_file( $bundle_name );
                }
            }

            echo esc_html__( 'Settings saved', 'woocart-defaults' );
        }

        exit;
    }

    /**
     * Compare bundle differences.
     *
     * @access public
     */
    public function load_diff() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundle_name    = stripslashes( $_POST['data']['bundle_name'] );
            $comparison     = WCD()->readwrite->compare_bundle( $bundle_name );

            // The pretty-text-diff.js will do its best on these print_r()s.
            if ( isset( $comparison['file'] ) ) {
                $comparison['file'] = print_r( $comparison['file'], true );
            }

            if ( isset( $comparison['db'] ) ) {
                $comparison['db'] = print_r( $comparison['db'], true );
            }

            echo json_encode( $comparison );
        }

        exit;
    }

    /**
     * Push settings to filesystem.
     *
     * @access public
     */
    public function push_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundle_name = stripslashes( $_POST['data']['bundle_name'] );

            WCD()->readwrite->push_bundle( $bundle_name );
            echo esc_html__( 'Push successful', 'woocart-defaults' );
        }

        exit;
    }

    /**
     * Pull settings into DB.
     *
     * @access public
     */
    public function pull_settings() {
        if ( current_user_can( 'manage_options' ) ) {
            $bundle_name = stripslashes( $_POST['data']['bundle_name'] );

            WCD()->readwrite->pull_bundle( $bundle_name );
            echo esc_html__( 'Pull successful', 'woocart-defaults' );
        }

        exit;
    }

}
