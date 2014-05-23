<?php

class WPCFM_Registry
{

    function __construct() {
        add_filter( 'wpcfm_configuration_items', array( $this, 'disallowed_items' ) );
    }


    /**
     * Each configuration item is a key/value pair
     * The `wpcfm_configuration_items` filter allows devs to
     * register additional configuration items
     * 
     * @since 1.0.0
     */
    function get_configuration_items() {
        global $wpdb;

        $items = array();

        $sql = "
        SELECT option_name, option_value FROM $wpdb->options
        WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
        ORDER BY option_name";
        $results = $wpdb->get_results( $sql );

        foreach ( $results as $result ) {
            $items['wp_options'][ $result->option_name ] = $result->option_value;
        }

        return apply_filters( 'wpcfm_configuration_items', $items );
    }


    /**
     * Register namespaces
     */
    function get_namespaces() {
        $namespaces = array(
            'wp_options' => 'WP Options'
        );

        return apply_filters( 'wpcfm_namespaces', $namespaces );
    }


    /**
     * Ignore certain configuration settings
     * 
     * @since 1.0.0
     */
    function disallowed_items( $items ) {

        $disallowed_items = array(
            'auth_key',
            'auth_salt',
            'cron',
            'db_version',
            'home',
            'nonce_key',
            'nonce_salt',
            'rewrite_rules',
            'siteurl',
        );

        foreach ( $disallowed_items as $row ) {
            unset( $items['wp_options'][ $row ] );
        }

        return $items;
    }
}
