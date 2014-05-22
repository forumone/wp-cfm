<?php

class WPCFM_Registry
{

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
        SELECT option_name, option_value
        FROM $wpdb->options
        WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
        ORDER BY option_name";

        $results = $wpdb->get_results( $sql );

        foreach ( $results as $result ) {
            $items['wp_options'][ $result->option_name ] = $result->option_value;
        }

        return apply_filters( 'wpcfm_configuration_items', $items );
    }
}
