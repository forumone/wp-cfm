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

		if ( is_network_admin() ) {
			$sql = "
			SELECT meta_key as option_name, meta_value as option_value FROM $wpdb->sitemeta
			WHERE meta_key NOT LIKE '_transient%' AND meta_key NOT LIKE '_site_transient%'
			ORDER BY meta_key";
		} else {
			$sql = "
			SELECT option_name, option_value FROM $wpdb->options
			WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
			ORDER BY option_name";
		}
        $results = $wpdb->get_results( $sql );

        foreach ( $results as $result ) {
            $items[ $result->option_name ] = array(
                'value' => $result->option_value
            );
        }

        return apply_filters( 'wpcfm_configuration_items', $items );
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
            'logged_in_key',
            'logged_in_salt',
            'nonce_key',
            'nonce_salt',
            'rewrite_rules',
            'siteurl',
        );

        foreach ( $disallowed_items as $row ) {
            unset( $items[ $row ] );
        }

        return $items;
    }
}
